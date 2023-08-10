<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Role Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('appeal/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Roles</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List</h3>
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                       data-target="#addRoleModal"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;
                        Add Role</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="departments" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Role Name</th>
                            <th>Role Slug</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th width="5%">#</th>
                            <th>Role Name</th>
                            <th>Role Slug</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>
<div class="modal fade" id="addRoleModal">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Add Role</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="process_body"></span>
                <form id="add_role_form" method="POST">
                    <input type="hidden" id="role_id" name="role_id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role_name">Role Name</label>
                                <input type="text" class="form-control" name="role_name" id="role_name" required>
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role_slug">Role Slug</label>
                                <input type="text" class="form-control" name="role_slug" id="role_slug" required>
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="role_name">Role Access (First Appeal)</label>
                        </div>
                    </div>
                    <hr class="mt-0">
                    <div class="row">
                        <?php foreach ($access_list as $key => $val) :
                        if(strpos($val->slug,'second-appeal') === false) {
                            ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="customCheck<?= $key ?>" name="roles[]"
                                               class="custom-control-input" value="<?= $val->{'_id'}->{'$id'} ?>">

                                        <label class="custom-control-label"
                                               for="customCheck<?= $key ?>"><?= $val->display_name ?></label>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }endforeach; ?>
                        <div class="text-danger error"></div>

                    </div>
                    <hr class="mb-0">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="role_name">Role Access (Second Appeal)</label>
                        </div>
                    </div>
                    <hr class="mt-0">
                    <div class="row">
                        <?php foreach ($access_list as $key => $val) :
                            if(strpos($val->slug,'second-appeal') !== false) {
                                ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" id="customCheck<?= $key ?>" name="roles[]"
                                                   class="custom-control-input" value="<?= $val->{'_id'}->{'$id'} ?>">

                                            <label class="custom-control-label"
                                                   for="customCheck<?= $key ?>"><?= $val->display_name ?></label>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            endforeach; ?>
                        <div class="text-danger error"></div>

                    </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" type="button" class="btn btn-default float-right" data-dismiss="modal">Close</a>
                <a href="javascript:void(0)" type="submit" id="add_role_submit"
                   class="btn btn-primary float-right">Save</a>
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="editRoleModal">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Update Role</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="process_body"></span>
                <form id="edit_role_form" method="POST">
                    <input type="hidden" id="role_id" name="role_id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role_name">Role Name</label>
                                <input type="text" class="form-control" name="role_name" id="role_name" required>
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="role_name">Role Access (First Appeal)</label>
                        </div>
                    </div>
                    <hr class="mt-0">
                    <div class="row" id="permissions">
                    </div>
                    <hr class="mb-0">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="role_name">Role Access (Second Appeal)</label>
                        </div>
                    </div>
                    <hr class="mt-0">
                    <div class="row" id="permissions-second">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" type="button" class="btn btn-default float-right" data-dismiss="modal">Close</a>
                <a href="javascript:void(0)" type="submit" id="update_role_submit" class="btn btn-primary float-right">Update</a>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Multi-select plugin -->


<script>
    $(document).ready(function () {

        var table = $('#departments').DataTable({
            "processing": true,
            language: {
                processing: '<div class="lds-ripple"><div></div><div></div></div>',
            },
            "pagingType": "full_numbers",
            "pageLength": 25,
            "serverSide": true,
            "orderMulti": false,
            "columnDefs": [{
                "width": "15%",
                "targets": 0
            },
                {
                    "targets": 3,
                    "orderable": false,
                    "sClass": "text-center",
                },
            ],
            "columns": [{
                "data": "sl_no"
            },
                {
                    "data": "role_name"
                },
                {
                    "data": "role_slug"
                },
                {
                    "data": "action"
                },
            ],
            "ajax": {
                url: "<?php echo site_url("appeal/roles/get_records") ?>",
                type: 'POST',
                beforeSend: function () {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function () {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },

        });

        //Add Department
        $(document).on('click', '#add_role_submit', function () {
            var modal = $('#addRoleModal');
            var role_name = modal.find('#role_name');
            var role_slug = modal.find('#role_slug');
            if (role_name == null || role_name === '') {
                modal.find('#role_name').addClass('is-invalid');
                modal.find('#role_name').next('.error').text('Please insert role name');
            } else if (role_slug == null || role_slug === '') {
                role_slug.addClass('is-invalid');
                role_slug.next('.error').text('Please insert slug name');
            } else {
                modal.find('.is-invalid').removeClass('is-invalid');
                modal.find('.error').text('');
                $(this).text('Saving...');
                var data = $('#add_role_form').serializeArray();
                $.post('<?= base_url('appeal/roles/add') ?>', data, function (data) {
                    if (data.status == true) {
                        table.draw();
                        modal.modal('hide');
                        $('#add_role_form').reset();
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Role Added successfully',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong! Please try again',
                        })
                        $(this).text('Save');
                    }
                })
            }
        });

        //Edit View Department
        $(document).on('click', '.editRole', function () {
            var role_id = $(this).attr('data-id');
            swal.fire({
                title: 'Please wait.',
                allowEscapeKey: false,
                showCloseButton: true,
                allowOutsideClick: () => !Swal.isLoading(),
                onOpen: () => {
                    Swal.showLoading();
                    // var role_id = $(this).attr('data-id');
                    $.post('<?= base_url('appeal/roles/get_role_info') ?>', {
                        'role_id': role_id,
                    }, function (jsn) {
                        if (jsn.status == true) {
                            var modal_box = $('#editRoleModal');
                            modal_box.find('#modal-title').text('Update Role');
                            modal_box.find('#role_id').val(jsn.data._id.$id);
                            modal_box.find('#role_name').val(jsn.data.role_name);
                            $('#permissions').empty();
                            $('#permissions-second').empty();
                            $(jsn.permissions).each(function (key, val) {
                                var checked = (val.checked) ? 'checked' : '';
                                if ((val.slug).indexOf('second-appeal') === -1) {
                                    $('#permissions').append('<div class="col-md-3">\
                                <div class="form-group">\
                                    <div class="custom-control custom-checkbox">\
                                        <input ' + checked + ' type="checkbox" id="check' + key + '" name="editroles[]" class="custom-control-input" value="' + val._id.$id + '" >\
                                        <label class="custom-control-label" for="check' + key + '">' + val.display_name + '</label>\
                                    </div>\
                                </div>\
                            </div>');
                                }

                                if ((val.slug).indexOf('second-appeal') !== -1) {
                                    $('#permissions-second').append('<div class="col-md-3">\
                                <div class="form-group">\
                                    <div class="custom-control custom-checkbox">\
                                        <input ' + checked + ' type="checkbox" id="check' + key + '" name="editroles[]" class="custom-control-input" value="' + val._id.$id + '" >\
                                        <label class="custom-control-label" for="check' + key + '">' + val.display_name + '</label>\
                                    </div>\
                                </div>\
                            </div>');
                                }
                            });
                            Swal.close();
                            modal_box.modal('show');

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong! Please try again',
                            })
                        }
                    })
                }
            });
        })

        //Update Department
        $(document).on('click', '#update_role_submit', function () {
            var modal_box = $('#editRoleModal');
            var role_id = modal_box.find('#role_id').val();
            var role_name = modal_box.find('#role_name').val();

            if (role_name == null || role_name === '') {
                modal_box.find('#role_name').addClass('is-invalid');
                modal_box.find('#role_name').next('.error').text('Please insert Role name');
            } else {
                modal_box.find('.is-invalid').removeClass('is-invalid');
                modal_box.find('.error').text('');
                var anc = $(this);
                anc.text('Updating..');
                var data = $('#edit_role_form').serializeArray();
                $.post('<?= base_url('appeal/roles/update') ?>', data, function (data) {
                    anc.text('Update');
                    if (data.status == true) {
                        table.draw();
                        modal_box.modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Roles Updated successfully',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.error_msg ? data.error_msg : 'Something went wrong!',
                        })
                        modal_box.modal('hide');
                    }
                })
            }


        })

        //When model is closed
        $('#addRoleModal').on('hidden.bs.modal', function (e) {
            $(this).find('#update_role_submit').attr('id', 'add_role_submit');
            $(this).find('#add_role_submit').text('Add');
            $(this).find('#role_name').val('');
            $(this).find('#role_id').val('');
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.error').text('');
            $(this).find('#modal-title').text('Add Role');
            ;
        })


    }); //End of ready function
</script>