<link href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.css">
<!-- DataTables -->
<script src="<?= base_url("assets/"); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>




<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark text-uppercase">Grievance</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("mis"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Grievance</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div><!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-university"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total <br> <small>(for current year)</small></span>
                            <a href="#">
                                <span class="info-box-number" id="total">
                                    <div class="spinner-grow text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </span>
                            </a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Under Process <br> <small>(for current year)</small></span>
                            <span class="info-box-number" id="under_process">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-file"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending <br> <small>(for current year)</small></span>
                            <a href="#">
                                <span class="info-box-number" id="pending">
                                    <div class="spinner-grow text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </span>
                            </a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-square"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Resolved <br> <small>(for current year)</small></span>
                            <span class="info-box-number" id="resolved">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grievance Filter</h3>
                </div>
                <div class="card-body">
                    <form id="revenue-filter-form" method="post" action="#">
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <select name="departments" id="departments" class="form-control" required>
                                    <option value="">Select Departments</option>
                                    <?php
                                    if (!empty($departments)) {
                                        foreach ($departments as $dep) { ?>
                                            <option value="<?= $dep->department_id ?>"><?= $dep->department_name ?></option>
                                        <?php }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <select name="services" id="services" class="form-control" required>
                                    <option>Select Services</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <select class="form-control select2" name="district" id="district" data-parsley-errors-container="#district_error_container" required>
                                    <?php
                                    if(!empty($districtList)){
                                        ?>
                                        <option value="">Choose a district</option>
                                        <?php
                                        foreach ($districtList as $district){
                                            ?>
                                            <option value="<?=$district->{'distcode'}?>" <?=(isset($old['district']) && $old['district'] == $district->{'distcode'}) ? 'selected' : ''?>><?=$district->distname?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <small class="text-danger" id="district_error_container"></small>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <!--                                <label for="date-range" class="col-sm-6 col-form-label text-right">Select Date Range:</label>-->
                                <input id="date-range" class="form-control" type="text" name="daterange" value="" required/>
                                <input type="hidden" id="start_date" name="start_date" value="" />
                                <input type="hidden" id="end_date" name="end_date" value="" />
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <button type="submit" class="btn btn-outline-info" id="showFilter">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="revenue-table" class="table table-bordered table-hover table-striped" style="width:100%">
                            <thead>
                            <tr class="table-header">
                                <th>#</th>
                                <th>Registration Number</th>
                                <th>Name</th>
                                <th>Grievance Category</th>
                                <th>Date of Receipt</th>
                                <th>Current Status</th>
                            </tr>
                            </thead>
                            <tbody class="small-text">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</section>
</div>


<script>
    var revenueFilterFormRef = $("#revenue-filter-form")
    var revenueFilterBtnRef = $("#showFilter")
    var grievanceGetRecordsUrl = '<?php echo base_url("mis/grievance/get_records") ?>'
    $(document).ready(function() {
        revenueFilterFormRef.parsley();
        $.get('<?= base_url("mis/grievance/get-counted-statistics") ?>', function(data, status) {
            $('#total').text(data.total);
            $('#under_process').text(data.under_process);
            $('#pending').text(data.pending);
            $('#resolved').text(data.resolved);

        });
        const table = $('#revenue-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel'
            ],
            "processing": true,
            language: {
                processing: '<div class="lds-ripple"><div></div><div></div></div>',
            },
            "pagingType": "full_numbers",
            "pageLength": 25,
            "serverSide": true,
            "orderMulti": false,
            "columnDefs": [{
                "width": "5%",
                "targets": 0
            },
                {
                    "targets": 4,
                    "orderable": false
                }
            ],
            "columns": [
                {
                    "data": "#"
                },
                {
                    "data": "RegistrationNumber"
                },
                {
                    "data": "Name"
                },
                {
                    "data": "grievanceCategory"
                },
                {
                    "data": "DateOfReceipt"
                },
                {
                    "data": "CurrentStatus"
                }
            ],
            "ajax": {
                url: grievanceGetRecordsUrl,
                type: 'POST',
                data: function(d) {
                    // let deptId = $('#departments').val();
                    let serviceId = $('#services').val();
                    let startDate = $('#start_date').val();
                    let endDate = $('#end_date').val();
                    let district = $('#district').val();
                    // d.deptId = deptId;
                    d.serviceId = serviceId;
                    d.startDate = startDate;
                    d.endDate = endDate;
                    d.district = district;
                },
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },
        });

        $("#departments").on('change', function() {
            let deparment_id = $("#departments").val();
            let url = '<?= base_url("mis/grievance/get-services/") ?>' + deparment_id;
            $.get(url, function(data, status) {
                let res = JSON.parse(data)
                if (res) {
                    $('#services').empty();
                    $('#services').append(`<option value=""> Select Services</option>`);

                    for(const index in res){
                        if(res.hasOwnProperty(index)){
                            let item = res[index]
                            $('#services').append(`<option value="${item.service_id}">
                               ${item.service_name}
                             </option>`);
                        }
                    }
                }
            })
        })

        revenueFilterBtnRef.on('click', function(e) {
            e.preventDefault()
            if (revenueFilterFormRef.parsley().validate()) {
                table.draw();
            }
        })

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
    })
</script>