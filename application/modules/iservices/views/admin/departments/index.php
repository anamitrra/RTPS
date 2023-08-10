<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">

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
                    <h1>Department Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Departments</li>
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
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addDepartmentModal"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;
                        Add Department</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="departments" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Department ID</th>
                                <th>Department Name</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th width="5%">#</th>
                                <th>Department ID</th>
                                <th>Department Name</th>
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
<div class="modal fade" id="addDepartmentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Add Department</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="process_body"></span>
                <form id="add_official_form" method="POST">
                    <input type="hidden" id="dept_id" name="dept_id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="department_id">Department ID</label>
                                <input type="text" class="form-control" name="department_id" id="department_id" required>
                                <div class="text-danger error"></div>
                            </div>
                            <div class="form-group">
                                <label for="department_name">Department Name</label>
                                <input type="text" class="form-control" name="department_name" id="department_name" required>
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0)" type="button" class="btn btn-default float-right" data-dismiss="modal">Close</a>
                    <a href="javascript:void(0)" type="submit" id="add_department_submit" class="btn btn-primary float-right">Save</a>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Multi-select plugin -->



<script>
    $(document).ready(function(){

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
                    "data": "department_id"
                },
                {
                    "data": "department_name"
                },
                {
                    "data": "action"
                },
            ],
            "ajax": {
                url: "<?php echo site_url("admin/departments/get_records") ?>",
                type: 'POST',
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },

        });

        //Add Department
        $(document).on('click', '#add_department_submit', function(){
            var modal = $('#addDepartmentModal');
            var department_name = modal.find('#department_name').val();
            var department_id = modal.find('#department_id').val();

            if(department_id == null || department_id == ''){
                modal.find('#department_id').addClass('is-invalid');
                modal.find('#department_id').next('.error').text('Plase insert department ID');
            }else if(department_name == null || department_name == ''){
                modal.find('#department_id').removeClass('is-invalid');
                modal.find('#department_id').next('.error').text('');
                modal.find('#department_name').addClass('is-invalid');
                modal.find('#department_name').next('.error').text('Plase insert department name');
            }else{
                modal.find('.is-invalid').removeClass('is-invalid');
                modal.find('.error').text('');
                $(this).text('Saving...');

                $.post('<?=base_url('admin/departments/insert') ?>', {department_name:department_name,department_id:department_id}, function(data){
                    if(data.status == true){
                        table.draw();
                        modal.modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Department Added successfully',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }
                    else{
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


        //Delet Department
        $(document).on('click', '.deleteDepartment', function(){
            if (confirm("Are you sure?")) {
            var department_id = $(this).attr('data-id');

            $.post('<?=base_url('appeal/department/delete') ?>', {'department_id' : department_id}, function(data){
                if(data.status == true){
                    table.draw();
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Department Deleted successfully',
                        showConfirmButton: false,
                        timer: 2500
                    })
                }
                else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! Please try again',
                        })
                    }
                })
            }
            return false;
        });

        //Edit View Department
        $(document).on('click', '.editDepartment', function() {
            var depertment_id = $(this).attr('data-id');
            swal.fire({
                title: 'Please wait.',
                allowEscapeKey: false,
                showCloseButton: true,
                allowOutsideClick: () => !Swal.isLoading(),
                onOpen: () => {
                    Swal.showLoading();
                    // var depertment_id = $(this).attr('data-id');
                    $.post('<?= base_url('admin/departments/get_department_info') ?>', {
                        'depertment_id': depertment_id,
                    }, function(jsn) {
                        if (jsn.status == true) {
                            var modal_box = $('#addDepartmentModal');
                            modal_box.find('#modal-title').text('Update Department');
                            modal_box.find('#dept_id').val(jsn.data._id.$id);
                            modal_box.find('#department_id').val(jsn.data.department_id);
                            modal_box.find('#department_name').val(jsn.data.department_name);
                            modal_box.find('#add_department_submit').text('Update');
                            modal_box.find('#add_department_submit').attr('id', 'update_department_submit');
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
        $(document).on('click', '#update_department_submit', function(){
            var modal_box = $('#addDepartmentModal');
            var dept_id =  modal_box.find('#dept_id').val();
            var department_id =  modal_box.find('#department_id').val();
            var department_name =   modal_box.find('#department_name').val();

            if(department_name == null || department_name === ''){
                modal_box.find('#department_name').addClass('is-invalid');
                modal_box.find('#department_name').next('.error').text('Please insert department name');
            }else if(department_id == null || department_id === ''){
                modal_box.find('#department_id').addClass('is-invalid');
                modal_box.find('#department_id').next('.error').text('Please insert department id');
            }else{
                modal_box.find('.is-invalid').removeClass('is-invalid');
                modal_box.find('.error').text('');
                $(this).text('Updating..');

                $.post('<?=base_url('admin/departments/update') ?>', {dept_id:dept_id, department_id:department_id, department_name:department_name}, function(data){
                    if(data.status == true){
                        table.draw();
                        modal_box.modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Department Updated successfully',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            })
                        modal_box.modal('hide');
                    }
                })
            }


        })

        //When model is closed
        $('#addDepartmentModal').on('hidden.bs.modal', function (e) {
            $(this).find('#update_department_submit').attr('id', 'add_department_submit');
            $(this).find('#add_department_submit').text('Add');
            $(this).find('#department_name').val('');
            $(this).find('#department_id').val('');
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.error').text('');
            $(this).find('#modal-title').text('Add Department');;
        })


    }); //End of ready function
</script>
