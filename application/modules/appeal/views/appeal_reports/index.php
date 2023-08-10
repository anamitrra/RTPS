<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<script src="<?= base_url("assets/"); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>

<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Appeals Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Appeals</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Appeals Report</h3>
                    <!-- <a class="float-right btn btn-success btn-xs" href="<?= base_url("appeal/excel-export") ?>"><i class="fas fa-file-excel"></i> Export To Excel</a> -->
                    <a id="btn_download_excel" class="float-right btn btn-success btn-xs" href="#!"><i class="fas fa-file-excel"></i> Export To Excel</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group row">
                                <label for="services" class="col-sm-2 col-form-label text-left">Appeal Type:</label>
                                <div class="col-sm-8">
                                    <select id="appeal_type" class="form-control" name="appeal_type" >
                                    <option value="1">First Appeal</option>
                                    <option value="2">Second Appeal</option>
                                     
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group row">
                                <label for="services" class="col-sm-2 col-form-label text-left">Select Service(s):</label>
                                <div class="col-sm-8">
                                    <select id="services" class="form-control" name="services" >
                                    <option value="">Select Service</option>
                                        <?php foreach ($services as $key => $value) : ?>
                                            <option value="<?= $value->{'_id'}->{'$id'} ?>"><?= $value->service_name ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">                            
                                
                                <input id="date-range" class="form-control form-control-sm" type="text" name="daterange" value="" />
                                <input type="hidden" id="start_date" value="" />
                                <input type="hidden" id="end_date" value="" />
                            </div>
                        
                    </div>
                    <div class="row">
                    <div class="col-md-8">
                            <div class="form-group row">
                                <label for="users" class="col-sm-2 col-form-label text-left">Select User:</label>
                                <div class="col-sm-8">
                                    <select id="users" class="form-control" name="user">
                                        <option value=""></option>
                                        <?php foreach ($users as $key => $value) : ?>
                                            <option value="<?= $value->{'_id'}->{'$id'} ?>"><?= $value->name.'('.$value->designation.')' ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                </div>
                            </div>

                                    
                        <div class="col-sm-2 offset-sm-2">
                                <a href="#!" class="btn btn-block btn-primary mb-2" id="sub"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search</a>
                            </div>
                        
                    </div>
                   
                    <table id="apeals" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <!-- <th>Application Ref No.</th> -->
                                <th>Appeal Id.</th>
                                <th>Applicant Name</th>
                                <th>Service Name</th>
                                <th>Contact Number</th>
                                <th>Appeal Date</th>
                                <th>District</th>
                                <th>Office</th>
                                <th>Process Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th width="5%">#</th>
                                <!-- <th>Application Ref No.</th> -->
                                <th>Appeal Id.</th>
                                <th>Applicant Name</th>
                                <th>Service Name</th>
                                <th>Contact Number</th>
                                <th>Appeal Date</th>
                                <th>District</th>
                                <th>Office</th>
                                <th>Process Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#services').select2({
			closeOnSelect : false,
			placeholder : "Select Services ",
			allowHtml: true,
			allowClear: true,
			tags: false // создает новые опции на лету
		});
        $('#users').select2({
			closeOnSelect : false,
			placeholder : "Select User",
			allowHtml: true,
			allowClear: true,
			tags: false // создает новые опции на лету
		});
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
                    "orderable": false
                }
            ],
            "columns": [{
                    "data": "sl_no"
                },
                // {
                //     "data": "appl_ref_no"
                // }, 
                {
                    "data": "appeal_id"
                },
                 {
                    "data": "name_of_service"
                },
                {
                    "data": "name"
                },
                {
                    "data": "contact_no"
                },
                {
                    "data": "appeal_date",
                },
                {
                    "data": "district",
                },
                  {
                    "data": "location_name",
                }, 
                {
                    "data": "process_status",
                },
                {
                    "data": "action"
                },
            ],
            "ajax": {
                url: "<?php echo site_url("appeal/appeal_reports/get_records") ?>",
                type: 'POST',
                data: function(d) {
                                        d.start_date = $('#start_date').val();
                                        d.end_date = $('#end_date').val();
                                        d.services = $('#services').val();
                                        d.appeal_type = $('#appeal_type').val();
                                        d.user = $('#users').val();
                                    },
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },
        });
        $('#sub').on('click', function() {
                                //$("#filters").empty();
                                //$("#filters").append("" + $("#category_of_traveller").val() + " | " + $("#critical_illness").val() + "" + " | " + $("#gender").val() + "" + " | " + $("#traveling_as").val() + "" + " | " + $("#from_state").val() + " | " + $("#from_district").val() + "" + " | " + $("#to_district").val() + "" + " | " + $("#age_of_traveller").val() + "");
                                table.draw();
                            });
        $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
            $('#start_date').val("");
            $('#end_date').val("");
            $('input[name="daterange"]').val("");
            table.draw();
        });
        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
            table.draw();
        });
        $("#clear").click(function() {
            $("select").val("");
            $("#filters").empty();
            table.draw();
        });

        $(document).on('click','#btn_download_excel',function(){
          
            let param='<?= base_url("appeal/excel-export?") ?>'+"start_date="+$('#start_date').val()+"&end_date="+$('#end_date').val()+"&services="+$('#services').val()+"&appeal_type="+$('#appeal_type').val()+"&users="+$('#users').val();
            window.location.href= param;
        })
        // Get Application's details
        $(document).on("click", ".modal-show", function() {
            console.log("modal");
            $("#modal-title").empty().append("Getting Data Of " + $(this).attr("data-appl_ref_no") + ".Please Wait....");
            $('#view-modal-body').empty().html('<div class="lds-ripple"><div></div><div></div></div>');
            var data_id = $(this).attr("data-id");
            var ref_no = $(this).attr("data-appl_ref_no");
            $.ajax({
                url: "<?php echo site_url("applications/view") ?>",
                type: 'GET',
                data: {
                    data_id
                },
                dataType: "html",
                success: function(data) {
                    $('#view-modal-body').empty().html(data);
                    $("#modal-title").empty().append("" + ref_no + "");
                }
            });
            $("#view_traveller").modal("show");
        });
        $('input[name="daterange"]').daterangepicker({
                                opens: 'left',
                                linkedCalendars: false,
                                showDropdowns: true,
                                startDate: moment().subtract(29, 'days'),
                                endDate: moment(),
                                locale: {
                                    "format": "DD/MM/YYYY",
                                    "separator": " - ",
                                    "applyLabel": "Apply Date Range",
                                    "cancelLabel": "Clear Selection",
                                    "fromLabel": "From",
                                    "toLabel": "To",
                                    "customRangeLabel": "Custom",
                                    "weekLabel": "W",
                                    "daysOfWeek": [
                                        "Su",
                                        "Mo",
                                        "Tu",
                                        "We",
                                        "Th",
                                        "Fr",
                                        "Sa"
                                    ],
                                    "monthNames": [
                                        "January",
                                        "February",
                                        "March",
                                        "April",
                                        "May",
                                        "June",
                                        "July",
                                        "August",
                                        "September",
                                        "October",
                                        "November",
                                        "December"
                                    ],
                                    "firstDay": 1
                                },
                            }, function(start, end, label) {
                                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));

                                //table.draw();
                            });

    });
</script>
<div class="modal fade" id="view_traveller">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="view-modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Multi-select plugin -->