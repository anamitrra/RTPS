<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
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
                    <h1>Services Under Department</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Department</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section>
    <div class="container">
    <div class="container-fluid">
    <div class="row ">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dept_id">Department</label>
                                <select class="custom-select" name="dept_id" id="dept_id">
                                    <option value="" selected>Select a Department</option>
                                    <?php if (!empty($departments)) {
                                        foreach ($departments as $department) {
                                    ?>
                                            <option value="<?= $department->department_id ?>"><?= $department->department_name ?></option>
                                    <?php
                                        }
                                    } ?>
                                </select>
                                <div class="text-danger error"></div>
                            </div>
                        </div>
    </div>
    </div>
    </div>
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Services List</h3>
                    <!-- <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addServiceModal"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;
                        Add Service</a>
                </div> -->
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="services" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Service ID</th>
                                <th>Service Name</th>
                                <th>Department</th>
                                <th>Timeline</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th width="5%">#</th>
                                <th>Service ID</th>
                                <th>Service Name</th>
                                <th>Department</th>
                                <th>Timeline</th>
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
<script>
    $(document).ready(function() {
        // var table = $('#services').DataTable({
        //     "processing": true,
        //     language: {
        //         processing: '<div class="lds-ripple"><div></div><div></div></div>',
        //     },
        //     "pagingType": "full_numbers",
        //     "pageLength": 25,
        //     "serverSide": true,
        //     "orderMulti": false,
        //     "columnDefs": [{
        //             "width": "15%",
        //             "targets": 0
        //         },
        //         {
        //             "targets": 3,
        //             "orderable": false
        //         },
        //         {
        //             "targets": 4,
        //             "orderable": false,
        //         },
        //         {
        //             "targets": 5,
        //             "orderable": false,
        //             "sClass"    : 'text-center',
        //         }
        //     ],
        //     "columns": [{
        //             "data": "sl_no"
        //         },
        //         {
        //             "data": "service_id"
        //         },
        //         {
        //             "data": "service_name"
        //         },
        //         {
        //             "data": "dept_name"
        //         },
        //         {
        //             "data": "timeline",
        //         },
        //         {
        //             "data": "action"
        //         },
        //     ],
        //     "ajax": {
        //         url: "<?php //echo base_url("mis/online/get_records") ?>",
        //         type: 'POST',
        //         beforeSend: function() {
        //             $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
        //         },
        //         complete: function() {
        //             $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
        //         }
        //     },          
        
        // });
        $(document).on('click', '#add_service_submit', function() {
            var modal = $('#addServiceModal');
            var service_id = modal.find('#service_id').val();
            var dept_id = modal.find('#dept_id').val();
            var service_name = modal.find('#service_name').val();
            var service_timeline = modal.find('#service_timeline').val();
            if (dept_id == null || dept_id == '') {
                modal.find('#dept_id').addClass('is-invalid');
                modal.find('#dept_id').next('.error').text('Please select department.');
            } else if (service_name == null || service_name == '') {
                modal.find('#dept_id').removeClass('is-invalid');
                modal.find('#dept_id').next('.error').text('');
                modal.find('#service_name').addClass('is-invalid');
                modal.find('#service_name').next('.error').text('Plase insert service name');
            } else if (service_id == null || service_id == '') {
                modal.find('.is-invalid').removeClass('is-invalid');
                modal.find('.error').text('');
                modal.find('#service_id').addClass('is-invalid');
                modal.find('#service_id').next('.error').text('Plase insert Service Id');
            } else if (service_timeline == null || service_timeline == '') {
                modal.find('.is-invalid').removeClass('is-invalid');
                modal.find('.error').text('');
                modal.find('#service_timeline').addClass('is-invalid');
                modal.find('#service_timeline').next('.error').text('Plase insert Service Timeline');
            } else {
                modal.find('.is-invalid').removeClass('is-invalid');
                modal.find('.error').text('');
                $(this).text('Saving..');
                $.post('<?= base_url('mis/online/add') ?>', {
                    dept_id: dept_id,
                    service_name: service_name,
                    service_id: service_id,
                    service_timeline: service_timeline
                }, function(data) {
                    if (data.status == true) {
                        table.draw();
                        modal.modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Service Added successfully',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                        $(this).text('Save');
                    }
                })
            }
        });

        //Delete Service
        $(document).on('click', '.deleteService', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    var service_obj_id = $(this).attr('data-id');
                    $.post('<?= base_url('mis/online/delete') ?>', {
                        'service_id': service_obj_id
                    }, function(data) {
                        console.log(data);
                        if (data.status == true) {
                            Swal.fire({
                                position: 'top',
                                icon: 'success',
                                title: 'Deleted Successfully',
                                showConfirmButton: false,
                                timer: 2500
                            })
                        } else {
                            console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong! Please try again',
                            })
                        }
                    })
                }
            })
            return false;
        });
        //Edit View Service
        $(document).on('click', '.editService', function() {
            swal.fire({
                title: 'Please wait.',
                allowEscapeKey: false,
                showCloseButton: true,
                allowOutsideClick: () => !Swal.isLoading(),
                onOpen: () => {
                    Swal.showLoading();
                    var service_id = $(this).attr('data-id');
                    $.post('<?= base_url('mis/online/get_service_info') ?>', {
                        'service_id': service_id
                    }, function(jsn) {
                        if (jsn.status == true) {
                            var modal_box = $('#addServiceModal');
                            modal_box.find('#modal-title').text('EDIT Service');
                            modal_box.find('#service_id').val(jsn.data.service_id);
                            modal_box.find('#service_timeline').val(jsn.data.service_timeline);
                            modal_box.find('#service_obj_id').val(service_id);
                            modal_box.find('#dept_id option:selected').removeAttr('selected');
                            modal_box.find('#dept_id').val(jsn.data.department_id).find("option[value=" + jsn.data.department_id + "]").attr('selected', true);
                            modal_box.find('#service_name').val(jsn.data.service_name);
                            modal_box.find('#add_service_submit').text('Update');
                            modal_box.find('#add_service_submit').attr('id', 'update_service_submit');
                            Swal.close();
                            modal_box.modal('show');
                        } else {
                            console.log(data);
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
        
        //Edit View Service
        $(document).on('click', '#update_service_submit', function() {
            var modal_box = $('#addServiceModal');
            var service_id = modal_box.find('#service_id').val();
            var service_obj_id = modal_box.find('#service_obj_id').val();
            var dept_id = modal_box.find('#dept_id').val()
            var service_name = modal_box.find('#service_name').val();
            var service_timeline = modal_box.find('#service_timeline').val();
            if (dept_id == null || dept_id == '') {
                modal_box.find('#dept_id').addClass('is-invalid');
                modal_box.find('#dept_id').next('.error').text('Please select department.');
            } else if (service_name == null || service_name == '') {
                modal_box.find('#dept_id').removeClass('is-invalid');
                modal_box.find('#dept_id').next('.error').text('');
                modal_box.find('#service_name').addClass('is-invalid');
                modal_box.find('#service_name').next('.error').text('Plase insert service name');
            } else if (service_id == null || service_id == '') {
                modal_box.find('.is-invalid').removeClass('is-invalid');
                modal_box.find('.error').text('');
                modal_box.find('#service_id').addClass('is-invalid');
                modal_box.find('#service_id').next('.error').text('Plase insert Service Id');
            } else if (service_timeline == null || service_timeline == '') {
                modal_box.find('.is-invalid').removeClass('is-invalid');
                modal_box.find('.error').text('');
                modal_box.find('#service_timeline').addClass('is-invalid');
                modal_box.find('#service_timeline').next('.error').text('Plase insert Service Timeline');
            } else {
                modal_box.find('.is-invalid').removeClass('is-invalid');
                modal_box.find('.error').text('');
                $(this).text('Updating..');
                $.post('<?= base_url('mis/online/update') ?>', {
                    service_obj_id: service_obj_id,
                    service_id: service_id,
                    dept_id: dept_id,
                    service_name: service_name,
                    service_timeline: service_timeline
                }, function(data) {
                    if (data.status == true) {
                        modal_box.modal("hide");
                        table.draw();
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Service has been updaed successfully !',
                            showConfirmButton: false,
                            timer: 2500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                        modal_box.modal('hide');
                        modal_box.find('#dept_id option:selected').removeAttr('selected');
                        modal_box.find('#update_service_submit').attr('id', 'add_service_submit');
                        $(this).text('Save');
                    }
                })
            }
        });
 
        //data filetred based on department
        $("#dept_id").on('change', function() {
            let deparment_id = $("#dept_id").val();
           // alert(deparment_id);
            let url = '<?= base_url("mis/department_services/") ?>' + deparment_id;
            var table = $('#services').DataTable({
            "processing": true,
            language: {
                processing: '<div class="lds-ripple"><div></div><div></div></div>',
            },
            "paging": false,
             "searching": false,
             "destroy": true,
            "pagingType": "full_numbers",
            "pageLength": 25,
            "serverSide": true,
            "orderMulti": false,
            "columnDefs": [{
                    "width": "15%",
                    "targets": 0,
                    "orderable": false
                },
                {
                    "targets": 3,
                    "orderable": false
                },
                {
                    "targets": 4,
                    "orderable": false
                 },
                 {
                 "targets": 5,
                    "orderable": true,
                     "sClass"    : 'text-center',
                 }
            ],
            "columns": [{
                    "data": "sl_no"
                },
                {
                    "data": "service_id"
                },
                {
                    "data": "service_name"
                },
                 {
                     "data": "dept_name"
                 },
                {
                    "data": "timeline",
                },
                {
                    "data": "action",
                },
                
            ],
            "ajax": {
                url: url,
                type: 'POST',
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },          
        });
        table.column(3).visible(false);
        table.column(5).visible(false);
    });
        //When model is closed
        $('#addServiceModal').on('hidden.bs.modal', function(e) {
            $(this).find('#update_service_submit').attr('id', 'add_service_submit');
            $(this).find('#add_service_submit').text('Add');
            $(this).find('#dept_id option:selected').removeAttr('selected');
            $(this).find('#service_name').val('');
            $(this).find('#service_id').val('');
            $(this).find('#service_obj_id').val('');
            $(this).find('#service_timeline').val('');
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.error').text('');
            $(this).find('#modal-title').text('Add Service');;
        })
    }); //End of ready function
</script>