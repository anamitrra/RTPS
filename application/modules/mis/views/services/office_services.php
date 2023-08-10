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
                    <h1>Offices Under Department </h1>
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
                    <h3 class="card-title">Office List</h3>
                    <!-- <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addServiceModal"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;
                        Add Service</a>
                </div> -->
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="services" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Office Name</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th width="5%">#</th>
                                <th>Office Name</th>
                                
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
       

        //data filetred based on department
        $("#dept_id").on('change', function() {
            let deparment_id = $("#dept_id").val();
           //alert(deparment_id);
            let url = '<?= base_url("mis/department_office/") ?>' + deparment_id;
            var table = $('#services').DataTable({
            "processing": false,
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
                    "targets": 1,
                    "orderable": false
                }
            ],
            "columns": [{
                    "data": "sl_no"
                },
                {
                    "data": "office_name"
                }
                
                
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
        
    }); //End of ready function
</script>