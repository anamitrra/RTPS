<?php
    $this->lang->load('appeal');
    $edit_dps_id = null;
?>
<style>
    .select2-drop-active{
    margin-top: -25px;
}
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
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
                    <h1>Official Mapping Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Official Details</li>
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
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right" id="add_official_button"><i
                                class="fas fa-plus" aria-hidden="true"></i>&nbsp;
                        Add Official Mapping</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <span id="process_body_s"></span>
                    <table id="apeals" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Service Name</th>
                            <th>Location</th>
                            <th>DA (Appellate Authority)</th>
                            <th>Appellate Authority</th>
                            <th>DPS Name</th>
                            <th>DA (Tribunal)</th>
                            <th>Registrar</th>
                            <th>Chairman</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th width="5%">#</th>
                            <th>Service Name</th>
                            <th>Location</th>
                            <th>DA (Appellate Authority)</th>
                            <th>Appellate Authority</th>
                            <th>DPS Name</th>
                            <th>DA (Tribunal)</th>
                            <th>Registrar</th>
                            <th>Chairman</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var st = $('#traveling_as').val();
        var table = $('#apeals').DataTable({
            "processing": true,
            language: {
                processing: '<div class="lds-ripple"><div></div><div></div></div>',
            },
            "pagingType": "full_numbers",
            "pageLength": 25,
            "serverSide": true,
            "orderMulti": false,
            "order": [[0, "desc"]],
            "columnDefs": [{
                "width": "15%",
                "targets": 0,
                "orderable": true
                },
                {
                    "targets": 1,
                    "orderable": false
                },
                {
                    "targets": 2,
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
                    "orderable": false
                },
                {
                    "targets": 6,
                    "orderable": false
                },
                {
                    "targets": 7,
                    "orderable": false
                },
                {
                    "targets": 8,
                    "orderable": false
                },
                {
                    "targets": 9,
                    "orderable": false
                }
            ],
            "columns": [{
                "data": "sl_no"
            },
                {
                    "data": "service_name"
                },
                {
                    "data": "location_id"
                },
                {
                    "data": "da_array"
                },
                {
                    "data": "appellate_auth"
                },
                {
                    "data": "dps_name"
                },
                {
                    "data": "da_tribunal_array"
                },
                {
                    "data": "registrar"
                },
                {
                    "data": "reviewing_auth"
                },
                {
                    "data": "action"
                }
                
            ],
            "ajax": {
                url: "<?php echo site_url("appeal/official_details/get_records") ?>",
                type: 'POST',
                beforeSend: function () {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function () {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },
        });

        // Get Application's details
        $(document).on("click", "#add_official_button", function () {
            $("#add_official").modal("show");
        });
        $(document).on("click", "#add_official_submit", function () {
            console.log("modal");
            $('#processing').fadeIn("slow");
            // $("#modal-title").empty().append("Please Wait....<div class='lds-ripple'><div></div><div></div></div>");
            $('#process_body').empty().append('<div class="alert alert-warning alert-dismissible fade show" role="alert">\
            <strong>Info!</strong> Processing....\
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
                </button>\
            </div>');
            var data = $('#add_official_form').serializeArray();
            $.ajax({
                url: "<?php echo site_url("appeal/official_details/create") ?>",
                type: 'POST',
                data: data,
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('#process_body').empty().append('<div class="alert alert-success alert-dismissible fade show" role="alert">\
            <strong>Success!</strong> Data Successfully submited.\
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
                </button>\
            </div>');
                        table.draw();
                        $("#add_official_form").trigger("reset");
                       location.reload();
                    } else {
                        $('#process_body').empty().append('<div class="alert alert-danger alert-dismissible fade show" role="alert">\
            <strong>Info!</strong>' + data.error_msg + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
                </button>\
            </div>');
                    }

                }
            });

        });

        // Update action
        $(document).on("click", "#update_official_submit", function () {
            // console.log("modal");
            // var bla = $('#lname').val();
            // console.log(bla);

            $('#processing').fadeIn("slow");
            // $("#modal-title").empty().append("Please Wait....<div class='lds-ripple'><div></div><div></div></div>");
            $('#process_body').empty().append('<div class="alert alert-warning alert-dismissible fade show" role="alert">\
            <strong>Info!</strong> Processing....\
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
                </button>\
            </div>');
            var data = $('#edit_official_form').serializeArray();
            $.ajax({
                url: "<?php echo site_url("appeal/official_details/update") ?>",
                type: 'POST',
                data: data,
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('#process_body').empty().append('<div class="alert alert-success alert-dismissible fade show" role="alert">\
            <strong>Success!</strong> Data Successfully submited.\
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
                </button>\
            </div>');
                        table.draw();
                        $("#edit_official_form").trigger("reset");
                       location.reload();
                    } else {
                        $('#process_body').empty().append('<div class="alert alert-danger alert-dismissible fade show" role="alert">\
            <strong>Info!</strong>' + data.error_msg + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
                </button>\
            </div>');
                    }

                }
            });

        });

        //Edit View Location
        $(document).on('click', '.editOfficialMapping', function() {

            // console.log("Hello");
            // var edit_dps_id;
            
            swal.fire({
                title: 'Please wait.',
                allowEscapeKey: false,
                showCloseButton: true,
                allowOutsideClick: () => !Swal.isLoading(),
                onOpen: () => {
                    Swal.showLoading();
                    var official_mapping_id = $(this).attr('data-id');
                    $.post('<?= base_url('appeal/official_details/get_official_mapping_info') ?>', {
                        'official_mapping_id': official_mapping_id, 
                    }, function(jsn) {

                        // console.log(jsn.data._id.$id);

                        // console.log(jsn.data.da_id_array);
                        // console.log(jsn.data.da_id_array.map(a => a.$oid));

                        // console.log(jsn.data.da_id_tribunal_array.map(a => a.$oid));


                        if (jsn.status == true) {
                            var modal_box = $('#editOfficeModal');
                            modal_box.find('#modal-title').text("Edit Official Mapping Details");
                            // modal_box.find('#modal-title').text(jsn.dept.0.department_id);

                            
                            modal_box.find('#official_details_id').val(jsn.data._id.$id);
                            modal_box.find('#edit_dept_id').val(jsn.dept[0].department_name);
                            modal_box.find('#edit_parent').val(jsn.dept[0].parent);
                            modal_box.find('#edit_service_id').val(jsn.service[0].service_name);
                            modal_box.find('#edit_location_id').val(jsn.location[0].location_name);
                            
                            // modal_box.find('#edit_dps_id').val(jsn.data.dps_id.$oid);
                            $("#edit_dps_id").val( jsn.data.dps_id.$oid).trigger('change');
                            $("#edit_appellate_id").val( jsn.data.appellate_id.$oid).trigger('change');
                            $("#edit_dealing_assistant").val(jsn.data.da_id_array.map(a => a.$oid)).trigger('change');
                            $("#edit_dealing_assistant_tribunal").val(jsn.data.da_id_tribunal_array.map(a => a.$oid)).trigger('change');
                            

                            // $("#edit_dps_id").select2("val", jsn.data.dps_id.$oid);

                            // dps_id
                            // Cookies.set("example", "foo");
                            // $.cookie("geeksforgeeks", "It is the data of the cookie");
                            // $.session.set("myVar", "Hello World!");
                            // $edit_dps_id = (jsn.location[0].location_name);
                            // modal_box.find('#location_name').val(jsn.data.location_name);
                            // modal_box.find('#add_location_submit').text('Update');
                            // modal_box.find('#add_location_submit').attr('id', 'update_location_submit');
                            // console.log(edit_dps_id);
                            // alert( Cookies.get("example") );
                            // $('#ckkk').append('<option>One </option>');
                           
                            Swal.close();
                            modal_box.modal('show');
                            // alert($.cookie('username')); 
                            // $.session.set("myVar", "Hello World!");
                            // $.cookie("Name", "123");
                            // $.session.set('username', 'test');

                           
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

    });
</script>
</div>
</div>
</div>
</div>
</section>
</div>
<div class="modal fade" id="add_official">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Add Official Mapping</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="view-modal-body">
                <span id="process_body"></span>
                <form id="add_official_form" method="POST" action="<?= base_url('appeal/process-login') ?>">
                    <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label for="department_name">Parent Organization</label>
                                        <select name="parent" id="parent" class="form-control" required>
                                            <option value="">-Select-</option>
                                            <option value="GOA">GOA(Govt Of Assam)</option>
                                            <option value="KAAC">KAAC</option>
                                            <option value="BTC">BTC</option>
                                            <option value="NCHAC">NCHAC</option>
                                        </select>
                                    
                                        <div class="text-danger error"></div>
                                    </div>

                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dept_id">Department</label>
                                <select class="select2" name="dept_id" id="dept_id"
                                        data-placeholder="Select a Department" style="width: 100%;">
                                  
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_id">Service</label>
                                <select class="select2" name="service_ids[]" id="service_id" data-placeholder="Select service" style="width: 100%;" multiple>
                                    <option value="">No data available</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location_id">Location ID</label>
                                <select class="select2" name="location_id" id="location_id"
                                        data-placeholder="Select a Location" style="width: 100%;">
                                
                                </select>
                            </div>
                        </div>
                    </div>
                    <legend class="h5">First Appeal</legend>
                    
                   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dealingAssistant">Dealing Assistant (Appellate)</label>
                                <select class="select2" name="dealing_assistant[]" id="dealingAssistant" data-placeholder="Select a Dealing Assistant" style="width: 100%;" multiple>
                                    <?php
                                    if (!empty($roleWiseUserList)) {
                                        ?>
                                        <option value=""> Choose One </option>
                                        <?php
                                        foreach ($roleWiseUserList as $userRole) {
                                            if ($userRole->slug === 'DA') {
                                                foreach ($userRole->users as $dps) {
                                                    if(!property_exists($dps,'da_type') || $dps->da_type !== 'Tribunal'){
                                                        ?>
                                                        <option value="<?= $dps->{'_id'} ?>"> <?= $dps->name ?> </option>
                                                        <?php
                                                    }
                                                   
                                                }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="appellate_id">Appellate Authority</label>
                                <select class="select2" name="appellate_id" id="appellate_id"
                                        data-placeholder="Select an Appellate Authority" style="width: 100%;">
                                    <?php
                                    if (!empty($roleWiseUserList)) {
                                        ?>
                                        <option value=""> Choose One </option>
                                        <?php
                                        foreach ($roleWiseUserList as $userRole) {
                                            if ($userRole->slug === 'AA') {
                                                foreach ($userRole->users as $aa) {
                                                    ?>
                                                    <option value="<?= $aa->{'_id'} ?>"> <?= $aa->name ?> </option>
                                                    <?php
                                                }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dps_id">DPS</label>
                                <select class="select2" name="dps_id" id="dps_id" data-placeholder="Select a DPS"
                                        style="width: 100%;">
                                    <?php
                                    if (!empty($roleWiseUserList)) {
                                        ?>
                                        <option value=""> Choose One </option>
                                        <?php
                                        foreach ($roleWiseUserList as $userRole) {
                                            if ($userRole->slug === 'DPS') {
                                                foreach ($userRole->users as $dps) {
                                                    ?>
                                                    <option value="<?= $dps->{'_id'} ?>"> <?= $dps->name ?> </option>
                                                    <?php
                                                }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                   
                    <legend class="h5">Second Appeal</legend>
                    
                    <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="dealingAssistant">Dealing Assistant (Tribunal)</label>
                                <select class="select2" name="dealing_assistant_tribunal[]" id="dealing_assistant_tribunal" data-placeholder="Select a Dealing Assistant" style="width: 100%;" multiple>
                                    <?php
                                    if (!empty($roleWiseUserList)) {
                                        ?>
                                        <option value=""> Choose One </option>
                                        <?php
                                        foreach ($roleWiseUserList as $userRole) {
                                            if ($userRole->slug === 'DA') {
                                                foreach ($userRole->users as $dps) {
                                                    if(isset($dps->da_type) && $dps->da_type === 'Tribunal'){
                                                        ?>
                                                        <option value="<?= $dps->{'_id'} ?>"> <?= $dps->name ?> </option>
                                                        <?php
                                                    }
                                                    
                                                }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dealingAssistant">Chairman</label>
                                <input type="text" class="form-control" disabled value="<?=$reviewing_authority?>" />
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dealingAssistant">Registrar</label>
                                <input type="text" class="form-control" disabled value="<?=$registrar_user?>" />
                                
                            </div>
                        </div>
                       
                    </div>
                </form>
            </div>
            <div class="modal-footer ">
                <a href="javascript:void(0)" class="btn btn-default float-right" data-dismiss="modal">Close</a>
                <a href="javascript:void(0)" id="add_official_submit" class="btn btn-primary float-right">Submit</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Multi-select plugin -->

<!-- Edit Official Mapping -->
<div class="modal fade" id="editOfficeModal">
    <!-- <?php echo $edit_dps_id ?> -->

    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="process_body"></span>
                <form id="edit_official_form" method="POST">
                <input type="hidden" id="official_details_id" name="official_details_id">
                <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label for="department_name">Parent Organization</label>
                                        <input disabled name="edit_parent" id="edit_parent" type="text" class="form-control">
                                        <div class="text-danger error"></div>
                                    </div>

                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dept_id">Department</label>
                                <input disabled name="edit_dept_id" id="edit_dept_id" type="text" class="form-control">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_id">Service</label>
                                <input disabled name="edit_service_id" id="edit_service_id" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location_id">Location ID</label>
                                <input disabled name="edit_location_id" id="edit_location_id" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <legend class="h5">First Appeal</legend>
                    
                   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dealingAssistant">Dealing Assistant (Appellate)</label>
                                
                                <select class="select2" name="edit_dealing_assistant[]" id="edit_dealing_assistant" data-placeholder="Select a Dealing Assistant" style="width: 100%;" multiple>
                                    <?php
                                    if (!empty($roleWiseUserList)) {
                                        ?>
                                        <option value=""> Choose One </option>
                                        <?php
                                        foreach ($roleWiseUserList as $userRole) {
                                            if ($userRole->slug === 'DA') {
                                                foreach ($userRole->users as $dps) {
                                                    if(!property_exists($dps,'da_type') || $dps->da_type !== 'Tribunal'){
                                                        ?>
                                                        <option value="<?= $dps->{'_id'} ?>"> <?= $dps->name ?> </option>
                                                        <?php
                                                    }
                                                   
                                                }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="appellate_id">Appellate Authority</label>
                                <select class="select2" name="edit_appellate_id" id="edit_appellate_id"
                                        data-placeholder="Select an Appellate Authority" style="width: 100%;">
                                    <?php
                                    if (!empty($roleWiseUserList)) {
                                        ?>
                                        <option value=""> Choose One </option>
                                        <?php
                                        foreach ($roleWiseUserList as $userRole) {
                                            if ($userRole->slug === 'AA') {
                                                foreach ($userRole->users as $aa) {
                                                    ?>
                                                    <option value="<?= $aa->{'_id'} ?>"> <?= $aa->name ?> </option>
                                                    <?php
                                                }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        

                    </div>
                    <div class="row">
                        <div class="col-md-6">
<!-- 
                        <input disabled name="edit_dps_id" id="edit_dps_id" type="text" class="form-control"> -->
                            <div class="form-group">
                                <label for="dps_id">DPS</label>
                                <select class="select2" name="edit_dps_id" id="edit_dps_id" data-placeholder="Select a DPS"
                                        style="width: 100%;">
                                    <?php
                                    if (!empty($roleWiseUserList)) {
                                        ?>
                                        <option value=""> Choose One </option>
                                        <?php
                                        foreach ($roleWiseUserList as $userRole) {
                                            if ($userRole->slug === 'DPS') {
                                                foreach ($userRole->users as $dps) {
                                                    ?>
                                                    <option value="<?= $dps->{'_id'} ?>"> <?= $dps->name ?> </option>
                                                    

                                                   
                                                    <?php
                                                }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                   
                    <legend class="h5">Second Appeal</legend>
                    
                    <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="dealingAssistant">Dealing Assistant (Tribunal)</label>
                                <select class="select2" name="edit_dealing_assistant_tribunal[]" id="edit_dealing_assistant_tribunal" data-placeholder="Select a Dealing Assistant" style="width: 100%;" multiple>
                                    <?php
                                    if (!empty($roleWiseUserList)) {
                                        ?>
                                        <option value=""> Choose One </option>
                                        <?php
                                        foreach ($roleWiseUserList as $userRole) {
                                            if ($userRole->slug === 'DA') {
                                                foreach ($userRole->users as $dps) {
                                                    if(isset($dps->da_type) && $dps->da_type === 'Tribunal'){
                                                        ?>
                                                        <option value="<?= $dps->{'_id'} ?>"> <?= $dps->name ?> </option>
                                                        <?php
                                                    }
                                                    
                                                }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dealingAssistant">Chairman</label>
                                <input type="text" class="form-control" disabled value="<?=$reviewing_authority?>" />
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dealingAssistant">Registrar</label>
                                <input type="text" class="form-control" disabled value="<?=$registrar_user?>" />
                                
                            </div>
                        </div>
                       
                    </div>
                  
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" type="button" class="btn btn-default float-right" data-dismiss="modal">Close</a>
                <a href="javascript:void(0)" type="submit" id="update_official_submit" class="btn btn-primary float-right">Update</a>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<!-- Select2 -->
<script src="<?= base_url('assets/plugins/select2/js/select2.min.js') ?>"></script>
<script>
    $(function () {
        var processBodyRef = $('#process_body');
        var serviceSelectRef = $('#service_id');
        var locationSelectRef = $('#location_id');

        var editServiceSelectRef = $('#edit_service_id');
        var editLocationSelectRef = $('#edit_location_id');


        console.log('Select 2 render');
        $('.select2').select2({
            placeholder: "Choose one",
            //allowClear: true
        });
                
        /*var dealingAssistants = $("#dealingAssistant").select2({
            closeOnSelect: false
            }).on("change", function(e){
            dealingAssistants.select2("open");
        });*/
        
        $('#dept_id').change(function () {
            let dept_id = $(this).find(':selected').val();
            let fetchServiceListUrl = '<?=base_url('appeal/official-details/get-service-list/:dept_id')?>';
            $('#process_body').empty().append('' +
                '<div class="alert alert-info alert-dismissible fade show" role="alert">\n' +
                '      <strong>Info!</strong> Processing....\n' +
                '       <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                '     <span aria-hidden="true">&times;</span>\n' +
                '     </button>\n' +
                '</div>');
            $.ajax({
                url: fetchServiceListUrl.replace(':dept_id', dept_id),
                type: 'POST',
                dataType: 'json',
                data: {dept_id},
                success: function (response) {
                    if (response.success) {
                        let serviceListToConvert = response.serviceList;
                        let serviceList = Object.keys(serviceListToConvert).map(key => {
                            return serviceListToConvert[key];
                        });
                        processBodyRef.empty();
                        serviceSelectRef.select2('destroy').empty().select2({
                            data: serviceList,
                            closeOnSelect: false
                        });
                        
                        /*var serviceIds = $("#service_id").select2({
                            closeOnSelect: false
                            }).on("change", function(e){
                            serviceIds.select2("open");
                        });*/
                        // serviceSelectRef.trigger('refresh');
                        serviceSelectRef.prop('disabled', false);

                        //for location 
                        let locationList = response.locationList;
                        if(locationList){
                            locationSelectRef.html('');
                            locationSelectRef.append($("<option></option>")
                                                .attr("value", "")
                                                .text("Select a Location")); 
                            $.each(locationList, function(key, value) {
                                locationSelectRef.append($("<option></option>")
                                                .attr("value", value._id.$id)
                                                .text(value.location_name)); 
                            });

                        }else{
                            locationSelectRef.html('');
                        }



                    } else {
                        serviceSelectRef.prop('disabled', true);
                        processBodyRef.empty().append('' +
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert">\n' +
                            '      <strong>Failed!</strong> Unable to load service list \n' +
                            '       <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                            '     <span aria-hidden="true">&times;</span>\n' +
                            '     </button>\n' +
                            '</div>');
                    }
                },
                error: function () {
                    serviceSelectRef.prop('disabled', true);
                    processBodyRef.empty().append('' +
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">\n' +
                        '      <strong>Failed!</strong> Unable to load service list \n' +
                        '       <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                        '     <span aria-hidden="true">&times;</span>\n' +
                        '     </button>\n' +
                        '</div>');
                }
            });
        });

        $(document).on('change',"#parent",function(){
            $('#service_id').empty();
            let p=$(this).val();
            swal.fire({
                title: 'Please wait.',
                allowEscapeKey: false,
                showCloseButton: true,
                allowOutsideClick: () => !Swal.isLoading(),
                onOpen: () => {
                    Swal.showLoading();
                    $.post('<?= base_url('appeal/departments/get_department_by_parent') ?>', {
                        'parent': p
                    }, function(jsn) {
                        if (jsn.status == true) {
                          //  console.log(jsn.data)
                          $("#dept_id").html('');
                          $('#dept_id')
                                    .append($("<option></option>")
                                                .attr("value", "")
                                                .text("Select a Department")); 
                            $.each(jsn.data, function(key, value) {
                                $('#dept_id')
                                    .append($("<option></option>")
                                                .attr("value", value._id.$id)
                                                .text(value.department_name)); 
                            });

                            Swal.close();
                            
                        } else {
                           // console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: jsn.data ? jsn.data : 'Something went wrong! Please try again',
                            })
                        }
                    })
                }
            });
        });


   

    });
</script>
