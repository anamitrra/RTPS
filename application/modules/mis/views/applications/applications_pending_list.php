<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/jquery-multi-select/src/example-styles.css" type="text/css">
<!-- DataTables -->
<script src="<?= base_url("assets/"); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/jquery-multi-select/src/jquery.multi-select.min.js"></script>
<!-- jQuery Multi-select box -->
<script src="/path/to/src/jquery.multi-select.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pending Applications</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("mis"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Pending Applications</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pending Applications</h3>
                        <a class="float-right btn btn-success btn-xs" href="<?= base_url("mis/applications/generatexls") ?>"><i class="fas fa-file-excel"></i> Export To Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="services" class="col-sm-4 col-form-label text-left">Select Service(s):</label>
                                    <div class="col-sm-8">
                                        <select id="services" class="form-control" name="services[]" multiple>
                                            <?php foreach ($services as $key => $value) : ?>
                                                <option value="<?= $value->service_id ?>"><?= $value->service_name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="date-range" class="col-sm-6 col-form-label text-right">Select Date Range:</label>
                                    <div class="col-sm-6">
                                        <input id="date-range" class="form-control form-control-sm" type="text" name="daterange" value="" />
                                    </div>
                                    <input type="hidden" id="start_date" value="" />
                                    <input type="hidden" id="end_date" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 offset-sm-10">
                                <a href="#!" class="btn btn-block btn-primary" id="sub"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="travellers-table" class="table table-bordered table-hover table-striped" style="width:100%">
                                <thead>
                                    <tr class="table-header">
                                        <th>Appl Ref No</th>
                                        <th>Applicant</th>
                                        <th>Sub Date</th>
                                        <th>Sub Office</th>
                                        <th>Task</th>
                                        <th>Due Date</th>
                                        <th>Version</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="small-text">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            var st = $('#traveling_as').val();
                            var table = $('#travellers-table').DataTable({
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
                                        "targets": 4,
                                        "orderable": false
                                    },
                                    {
                                        "targets": 6,
                                        "orderable": false
                                    }
                                ],
                                "columns": [{
                                        "data": "appl_ref_no"
                                    },
                                    {
                                        "data": "applicant_name"
                                    },
                                    {
                                        "data": "sub_date"
                                    },
                                    {
                                        "data": "sub_office"
                                    },
                                    {
                                        "data": "curr_task"
                                    },
                                    {
                                        "data": "due_date"
                                    },
                                    {
                                        "data": "version"
                                    },
                                    {
                                        "data": "action"
                                    },
                                ],
                                "ajax": {
                                    url: "<?php echo base_url("mis/applications/get_records_pending") ?>",
                                    type: 'POST',
                                    data: function(d) {
                                        d.start_date = $('#start_date').val();
                                        d.end_date = $('#end_date').val();
                                        d.services = $('#services').val();
                                        d.service_status = $('#service_status').val();
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
                            // Get Application's details
                            $(document).on("click", ".modal-show", function() {
                                console.log("modal");
                                $("#modal-title").empty().append("Getting Data Of " + $(this).attr("data-appl_ref_no") + ".Please Wait....");
                                $('#view-modal-body').empty().html('<div class="lds-ripple"><div></div><div></div></div>');
                                var data_id = $(this).attr("data-id");
                                var ref_no = $(this).attr("data-appl_ref_no");
                                $.ajax({
                                    url: "<?php echo base_url("mis/applications/view") ?>",
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
                </div>
            </div>
        </div>
</div>
</section>
</div>
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
<script>
    $(document).ready(function() {
        $('#services').multiSelect();
    });
</script>