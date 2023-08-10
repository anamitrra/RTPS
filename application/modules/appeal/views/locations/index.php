<?php 
// print_r($departments);
?>
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
                    <h1>Location Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Locations</li>
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
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addLocationModal"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;
                        Add Location</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="locations" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Location ID</th>
                                <th>Location Name</th>
                                <th>Department ID</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th width="5%">#</th>
                                <th>Location ID</th>
                                <th>Location Name</th>
                                <th>Department ID</th>
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
<div class="modal fade" id="addLocationModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Add Location</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <!-- <?php 
            print_r($departments[0]['department_id']);
            ?> -->
                <span id="process_body"></span>
                <form id="add_official_form" method="POST">
                    <input type="hidden" id="location_obj_id" name="location_obj_id" value="">
                    <div class="row">

                    <!-- <div class="col-md-12">
                            <div class="form-group">
                                <label for="dept_id">Department</label>
                                <select class="select2" name="dept_id" id="dept_id"
                                        data-placeholder="Select a Department" style="width: 100%;">
                                  
                                </select>
                            </div>
                        </div> -->

                        <div class="col-md-12 form-group">
                                <label>Department</label>
                                <select id="department_id"   name="department_id" class="form-control">
                                <option value="">Please Select</option>
                                <?php  if(!empty($departments))  { ?>
                                    
<?php foreach($departments as $department)  {?>
<option value="<?php echo (isset($department['department_id'] )) ? $department['department_id']  : '' ?>"><?php echo (isset($department['department_name'] )) ?  $department['department_name'] :$department['department_name']  ?></option>


<?php }  ?>

<?php }  else {?>

  <p>Records not found!</p>

<?php } ?>
                                   
                                </select>
                            </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="location_name">Location ID</label>
                                <input  type="text" class="form-control" name="location_id" id="location_id" required>
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="location_name">Location Name</label>
                                <input type="text" class="form-control" name="location_name" id="location_name" required>
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0)" type="button" class="btn btn-default float-right" data-dismiss="modal">Close</a>
                    <a href="javascript:void(0)" type="submit" id="add_location_submit" class="btn btn-primary float-right">Save</a>
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

        var table = $('#locations').DataTable({
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
                    "data": "location_id"
                },
                {
                    "data": "location_name"
                },
                {
                    "data": "department_id"
                },
                {
                    "data": "action"
                },
            ],
            "ajax": {
                url: "<?php echo site_url("appeal/locations/get_records") ?>",
                type: 'POST',
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },          
        
        });

    //Add Location
        $(document).on('click', '#add_location_submit', function(){
            
            var modal = $('#addLocationModal');
            var location_name = modal.find('#location_name').val();
            var location_id = modal.find('#location_id').val();
            var department_id = modal.find('#department_id').val();

            if(location_id == null || location_id === ''){
                modal.find('#location_id').addClass('is-invalid');
                modal.find('#location_id').next('.error').text('Please insert location id');
             }
             if(department_id == null || department_id === ''){
                modal.find('#department_id').addClass('is-invalid');
                modal.find('#department_id').next('.error').text('Please insert department');
             }
             else if(location_name == null || location_name === ''){
                modal.find('.is-invalid').removeClass('is-invalid');
                modal.find('.error').text('');
                modal.find('#location_name').addClass('is-invalid');
                modal.find('#location_name').next('.error').text('Please insert location name');
            }else{
                modal.find('.is-invalid').removeClass('is-invalid');
                modal.find('.error').text('');
                $(this).text('Saving..');


                $.post('<?=base_url('appeal/location/add') ?>', {location_name:location_name, location_id:location_id,department_id:department_id}, function(data){
                    if(data.status == true){
                        table.draw();
                        modal.modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Location Added successfully',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Input...',
                            text: 'Something went wrong! Please try again',
                            html:data.error_msg ?  data.error_msg : ''
                            })
                        $(this).text('Save');
                    }
                })
            }
        });


        //Delet location
        $(document).on('click', '.deleteLocation', function(){
            if (confirm("Are you sure?")) {
                var location_id = $(this).attr('data-id');

                $.post('<?=base_url('appeal/location/delete') ?>', {'location_id' : location_id}, function(data){
                    if(data.status == true){
                        table.draw();
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Location Deleted Successfully',
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

        //Edit View Location
        $(document).on('click', '.editLocation', function() {
            
            swal.fire({
                title: 'Please wait.',
                allowEscapeKey: false,
                showCloseButton: true,
                allowOutsideClick: () => !Swal.isLoading(),
                onOpen: () => {
                    Swal.showLoading();
                    var location_id = $(this).attr('data-id');
                    $.post('<?= base_url('appeal/location/get_location_info') ?>', {
                        'location_id': location_id,
                    }, function(jsn) {
                        if (jsn.status == true) {
                            var modal_box = $('#addLocationModal');
                            modal_box.find('#modal-title').text('Update Location');
                            modal_box.find('#location_obj_id').val(jsn.data._id.$id);
                            modal_box.find('#location_id').val(jsn.data.location_id);
                            modal_box.find('#location_id').prop('disabled', true);
                            modal_box.find('#department_id').val(jsn.data.department_id);
                            modal_box.find('#location_name').val(jsn.data.location_name);
                            modal_box.find('#add_location_submit').text('Update');
                            modal_box.find('#add_location_submit').attr('id', 'update_location_submit');
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

        //Edit Location
        $(document).on('click', '#update_location_submit', function(){
            var modal_box = $('#addLocationModal');
            var location_obj_id =  modal_box.find('#location_obj_id').val();
            var location_id     =  modal_box.find('#location_id').val();
            var location_name   =  modal_box.find('#location_name').val();
            var department_id   =  modal_box.find('#department_id').val();
            
            if(location_id == null || location_id === ''){
                modal_box.find('#location_id').addClass('is-invalid');
                modal_box.find('#location_id').next('.error').text('Please insert location id');
            }
            if(department_id == null || department_id === ''){
                modal.find('#department_id').addClass('is-invalid');
                modal.find('#department_id').next('.error').text('Please insert department');
             }
             else if(location_name == null || location_name === ''){
                modal_box.find('.is-invalid').removeClass('is-invalid');
                modal_box.find('.error').text('');
                modal_box.find('#location_name').addClass('is-invalid');
                modal_box.find('#location_name').next('.error').text('Please insert location name');
            }else{
                modal_box.find('.is-invalid').removeClass('is-invalid');
                modal_box.find('.error').text('');
                $(this).text('Updating..');
                modal_box.find('#location_id').prop('disabled', false);

                $.post('<?=base_url('appeal/location/update') ?>', {location_obj_id, location_id, location_name,department_id:department_id}, function(data){
                    if(data.status == true){
                        table.draw();
                        modal_box.modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Location Updated Successfully',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Input...',
                            text: 'Something went wrong! Please try again',
                             html:data.error_msg ?  data.error_msg : ''
                            })
                        modal_box.modal('hide');
                    }
                })
            }


        })

        //When model is closed
        $('#addLocationModal').on('hidden.bs.modal', function (e) {
            $(this).find('#update_location_submit').attr('id', 'add_location_submit');
            $(this).find('#add_location_submit').text('Add');
            $(this).find('#location_name').val('');
            $(this).find('#location_id').val('');
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.error').text('');
            $(this).find('#modal-title').text('Add Location');
            $(this).find('#location_id').prop('disabled', false);
        })

        $(".close").click(function(){
            $(this).find('#location_id').prop('disabled', false);
        });


    }); //End of ready function
</script>
