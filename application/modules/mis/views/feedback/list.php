<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/jquery-multi-select/src/example-styles.css" type="text/css">
<!-- DataTables -->
<script src="<?= base_url("assets/"); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>


<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Feedback</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("mis"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Feedback</li>
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
                        <div class="row">
                            <div class="col-lg-4 col-6">
                                <!-- small box -->
                                <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3 >
                                    <?= $overal_avg ?>
                                </h3>

                                    <p>Overall Average Ratings</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                
                                </div>
                            </div>

                            <div class="col-lg-4 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                <div class="inner">
                                    <h3 >
                                    <?= $avg_submission ?>
                                </h3>

                                    <p>Average Ratings On Submission</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <!-- small box -->
                                <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3 >
                                    <?= $avg_delivered ?>
                                </h3>

                                    <p>Average Ratings On Delivered</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                
                                </div>
                            </div>

                           
                        </div>
                    </div>
                    <div class="card-body">



                        <form id="filter-citizen-form" action="#" method="POST">
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <label for="date-range">Date Range</label>
                                    <input id="date-range" class="form-control" type="text" name="daterange" value="" />
                                    <input type="hidden" id="start_date" name="start_date" value="" />
                                    <input type="hidden" id="end_date" name="end_date" value="" />
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="submission_location">Services</label>
                                    <select name="service_id" id="service_id" class="form-control select2" autocomplete=off>
                                        <?php
                                        if (isset($serviceList)) {
                                        ?>
                                            <option value="" hidden>Please select a service</option>
                                            <?php
                                            foreach ($serviceList as $service) {
                                            ?>
                                                <option value="<?= strval($service->service_id) ?>"><?= $service->service_name?></option>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="" hidden>No Service found</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="submission_location">Office</label>
                                    <select name="submission_location" id="submission_location" class="form-control select2" autocomplete=off>
                                        <?php
                                        if (isset($locationList)) {
                                        ?>
                                            <option value="" hidden>Please select a location</option>
                                            <?php
                                            foreach ($locationList as $location) {
                                            ?>
                                                <option value="<?= $location->location_name ?>"><?= $location->location_name ?></option>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="" hidden>No location found</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="application_status">Feedback On</label>
                                    <select name="application_status" id="application_status" class="form-control select2" data-parsley-errors-container="#application-status-error-container" autocomplete=off>
                                        <option value="" hidden>Please select</option>

                                        <option value="submission">Submission</option>
                                        <option value="delivered">Delivered</option>

                                    </select>
                                    <span id="application-status-error-container"></span>
                                </div>
                            </div>

                            <button class="btn btn-outline-primary  font-weight-bold mt-3" id="citizen-filter-clear-btn">Clear Filter</button>
                            <!-- <p class="mt-2 mb-0"><span class="text-danger">*</span> <span class="small text-muted font-weight-bold">Apply at least one filter</span></p> -->
                            <button class="btn btn-outline-primary  font-weight-bold mt-3" id="citizen-filter-btn">Apply Filter</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Feedback List</h3>
                        <a class="float-right btn btn-success btn-xs" href="<?= base_url("mis/feedback/download_excel") ?>"><i class="fas fa-file-excel"></i> Export To Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">


                        <div class="table-responsive">
                            <table id="travellers-table" class="table table-bordered table-hover table-striped" style="width:100%">
                                <thead>
                                    <tr class="table-header">
                                        <th>Appl Ref No</th>
                                        <th>Service Name</th>
                                        <th>Department</th>
                                        <th>Office</th>
                                        <th>Feedback On</th>
                                        <th>Stars</th>
                                        <th>Remarks</th>
                                        <th>Dated</th>
                                        <!-- <th>Version</th> -->
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody class="small-text">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <script type="text/javascript">
                        var citizenFilterClearBtnRef = $('#citizen-filter-clear-btn')
                        var citizenFilterBtnRef = $('#citizen-filter-btn')
                        var citizenFilterFormRef = $('#filter-citizen-form')
                        var downloadExcelUrl = '<?= base_url('mis/citizen/download-excel') ?>';
                        var def1
                        $(document).ready(function() {
                            $('.select2').select2();
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
                                        "targets": 6,
                                        "orderable": false
                                    },
                                    {
                                        "targets": 7,
                                        "orderable": false
                                    },
                                ],
                                "columns": [{
                                        "data": "appl_ref_no"
                                    },
                                    {
                                        "data": "service_name"
                                    },
                                    {
                                        "data": "department_name"
                                    },
                                    {
                                        "data": "submission_location"
                                    },
                                    {
                                        "data": "feedback_on"
                                    },
                                    {
                                        "data": "stars"
                                    },
                                    {
                                        "data": "feedback_text"
                                    },
                                    {
                                        "data": "created_at"
                                    },
                                ],
                                "ajax": {
                                    url: "<?php echo base_url("mis/feedback/get_records") ?>",
                                    type: 'POST',
                                    data: function(d) {
                                        d.startDate = $('#start_date').val();
                                        d.endDate = $('#end_date').val();
                                        d.submission_location = $('#submission_location').val();
                                        d.feedback_on = $('#application_status').val();
                                        d.service_id = $('#service_id').val();
                                    },
                                    beforeSend: function() {
                                        $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                                    },
                                    complete: function() {
                                        $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                                    }
                                },
                            });



                            //date picker
                            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                                $('#start_date').val("");
                                $('#end_date').val("");
                                $('input[name="daterange"]').val("");
                                table.draw();
                            });
                            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                                $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
                                $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
                            });
                            $("#clear").click(function() {
                                $("select").val("");
                                $("#filters").empty();
                                table.draw();
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

                            citizenFilterBtnRef.on('click', function(e) {

                                e.preventDefault()
                                if (citizenFilterFormRef.parsley().validate()) {
                                    table.draw();
                                }
                            });
                            citizenFilterClearBtnRef.on('click', function(e) {
                                e.preventDefault()
                                $('#filter-citizen-form').trigger("reset");
                                //   $(this).closest('form').find("input[type=text], textarea").val("");
                                table.draw();

                            })



                        });
                    </script>
                </div>
            </div>
        </div>
</div>
</section>
</div>