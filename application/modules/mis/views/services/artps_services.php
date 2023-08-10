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
                    <h1>ARTPS Services</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">ARTPS Services</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notified Service List</h3>
                    <!-- <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addServiceModal"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;
                        Add Service</a> -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="services" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Service Name</th>
                                <th>Department</th>
                                <th>Council</th>
                                <th>Stipulated Timeline</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th width="5%">#</th>
                                <th>Service Name</th>
                                <th>Department</th>
                                <th>Council</th>
                                <th>Stipulated Timeline</th>
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
<div class="modal fade" id="addServiceModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Service Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="process_body"></span>
               
                   <div id="service_body"></div>
                
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" class="btn btn-default float-right" data-dismiss="modal">Close</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Multi-select plugin -->
<script>
    $(document).ready(function() {
        var table = $('#services').DataTable({
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
                    "orderable": false
                },
                {
                    "targets": 4,
                    "orderable": false,
                },
               
            ],
            "columns": [{
                    "data": "sl_no"
                },
                {
                    "data": "rtps_service"
                },
                {
                    "data": "department"
                },
                {
                    "data": "autonomous_council",
                },
                {
                    "data": "stipulated_timeline"
                },
                {
                    "data": "action"
                },
            ],
            "ajax": {
                url: "<?php echo base_url("mis/artps-services/get_records") ?>",
                type: 'POST',
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },          
        
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
                    var service = $(this).attr('data-id');
                    // console.log(JSON.parse(service_id));
                    var res=JSON.parse(service);
                    let charges="";
                    if(res.charges){
                        charges +="<ol>";
                        res.charges.forEach(element => {
                            charges +=" <li>"+element+"</li>";
                        });
                        charges +="</ol>";
                    }
                    
                    

                    let body=` <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_name">Service Name :</label>
                                <span id="service_name">`+res.rtps_service+`</span>
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_name">Notification No :</label>
                                `+res.notification_no+`
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_name">Notified On:</label>
                                `+res.date_of_notification+`
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_name">Stipulated Timeline :</label>
                                `+res.stipulated_timeline+`
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_name">Department:</label>
                                `+res.department+`
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_name">Council :</label>
                                `+res.autonomous_council+`
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_name">Appellate authority:</label>
                                `+res.designation_of_aa+`
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_name">DPS :</label>
                                `+res.designation_of_dps+`
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row"> 
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="service_name">Charges</label>
                                    `+charges+`
                                <div class="text-danger error"></div>
                            </div>
                        </div>
                       
                    </div>`;
                    var modal_box = $('#addServiceModal');
                    
                    modal_box.find('#service_body').html(body);
                    
                    Swal.close();
                    modal_box.modal('show');
                    
                }
            });
        })
       
    }); //End of ready function
</script>