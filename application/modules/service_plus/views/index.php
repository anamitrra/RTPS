<link href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
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
<script src="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>

<!-- Main content -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Welcome, <?=$this->session->userdata("name")?></h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=base_url("mis");?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid my-2">
            <div class="card">
                <div class="card-header">
                    <h5>Payment Log</h5>
                </div>
                <div class="card-body">
                    <form id="filter-form" method="POST" action="">
                        <div class="row">
                            <div class="col-4">
                                <label for="user_type">User Type</label>
                                <select name="user_type" id="user_type" class="form-control select2" required autocomplete="off" data-parsley-errors-container="#user-type-error-box">
                                    <option value="" hidden>Please select a user type</option>
                                    <option value="KIOSK">KIOSK</option>
                                    <option value="CTZN">Citizen</option>
                                    <option value="WithOutLogin">Without Login</option>
                                    <option value="ADMN">Administrator</option>
                                    <option value="SADMN">System Administrator</option>
                                </select>
                                <span id="user-type-error-box" class="text-danger"></span>
                            </div>
                            <div class="col-4">
                                <label for="payment_status">Payment Status</label>
                                <select name="payment_status" id="payment_status" class="form-control select2">
                                    <option value="" hidden>Please select a status</option>
                                    <option value="P">Pending</option>
                                    <option value="Y">Paid</option>
                                    <option value="N">Failed</option>
                                    <option value="NA">NA</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="date-range">Date Range <small class="text-muted">(max range one month)</small></label>
                                <input id="date-range" class="form-control" type="text" name="daterange" value="" required autocomplete="off"/>
                                <input type="hidden" id="start_date" name="start_date" value="" />
                                <input type="hidden" id="end_date" name="end_date" value="" />
                                <span id="date-range-error-box" class="text-danger"></span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-success mt-2" id="filter-button"> Filter</button>
                    </form>
                    <hr>
<!--                    <div class="text-info mb-2">Maximum date range is one month from the starting date</div>-->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="payment-log-table">
                            <thead>
                            <tr>
                                <th class="align-text-top">#</th>
                                <th class="align-text-top">Username</th>
                                <th class="align-text-top">User Type</th>
                                <th class="align-text-top">Service name</th>
                                <th class="align-text-top">Application Ref No.</th>
                                <th class="align-text-top">Amount</th>
                                <th class="align-text-top">Payment Status</th>
                                <th class="align-text-top">HOA Wise Amount</th>
<!--                                <th class="align-text-top">Action</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
    const paymentLogTableRef = $('#payment-log-table')
    const filterBtnRef       = $('#filter-button')
    const filterFormRef      = $('#filter-form')
    const dateRangeRef       = $('#date-range')
    const startDateRef       = $('#start_date')
    const endDateRef         = $('#end_date')
    const dateRangeErrorContianerRef = $('#date-range-error-box')
    $(function(){
        $('.select2').select2();
        const table = paymentLogTableRef.DataTable({
            "processing": true,
            language: {
                processing: '<div class="lds-ripple"><div></div><div></div></div>',
            },
            "pagingType": "full_numbers",
            "pageLength": 10,
            "serverSide": true,
            "orderMulti": false,
            "columnDefs": [
                {
                    "width": "5%",
                    "targets": 0
                },
                {
                    "width": "15%",
                    "targets": 1
                },
                {
                    "width": "10%",
                    "targets": 2
                },
                {
                    "width": "10%",
                    "targets": 3
                },
                {
                    "width": "5%",
                    "targets": 4
                },
                {
                    "width": "10%",
                    "targets": 5
                },
                {
                    "width": "10%",
                    "targets": 6
                },
                {
                    "width": "10%",
                    "targets": 7
                },
            ],
            "columns": [
                {
                    "data": "sl"
                },
                {
                    "data": "user_name"
                },
                {
                    "data": "sign_role",
                    "render" : function(data, type, row, meta) {
                        let userType = 'NA';

                        switch (data) {
                            case 'KIOSK':
                                userType = 'KIOSK';
                                break;
                            case 'CTZN':
                                userType = 'Citizen';
                                break;
                            case 'WithOutLogin':
                                userType = 'WithOutLogin';
                                break;
                            case 'ADMN':
                                userType = 'Administrator';
                                break;
                            case 'SADMN':
                                userType = 'System Administrator';
                                break;
                            default:
                                userType = 'NA';
                                break;
                        }
                        return userType;
                    }
                },
                {
                    "data": "service_name"
                },
                {
                    "data": "appl_ref_no"
                },
                {
                    "data": "transaction_amount"
                },
                {
                    "data": "payment_status",
                    "render" : function(data, type, row, meta){
                        let status = 'NA';
                        switch (data){
                            case 'P':
                                status = 'Pending';
                                break;
                            case 'Y':
                                status = 'Paid';
                                break;
                            case 'N':
                                status = 'Failed';
                                break;
                            default:
                                status = 'NA';
                                break;
                        }
                        return status;
                    },
                },
                {
                    "data": "hoaAmount"
                }
                // {
                //     "data": "action",
                //     "render": function(data, type, row, meta ){
                //         return '<a class="btn btn-sm btn-outline-warning"><span class="fa fa-eye"></span></a>';
                //     }
                // }
            ],
            "ajax": {
                url: '<?php echo base_url("service_plus/get-records") ?>',
                type: 'POST',
                data: function (d){
                    d.startDate = $('#start_date').val();
                    d.endDate = $('#end_date').val();
                    d.payment_status = $('#payment_status').val();
                    d.user_type = $('#user_type').val();
                },
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },
        });


        $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
            $('#start_date').val("");
            $('#end_date').val("");
            $('input[name="daterange"]').val("");
            // table.draw();
        });
        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
        });
        $("#clear").click(function() {
            $("select").val("");
            $("#filters").empty();
            // table.draw();
        });
        $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            linkedCalendars: false,
            showDropdowns: true,
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            dateLimit: {
                'months': 1,
                'days': -1
            },
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

        filterBtnRef.click(function(){
            let dateRangeHelpText = "select date range and check on apply date range from the menu";
            if(!startDateRef.val().length && !endDateRef.val().length){
                dateRangeErrorContianerRef.html(dateRangeHelpText);
                return;
            }else{
                dateRangeErrorContianerRef.html('');
            }
            if(filterFormRef.parsley().validate()){
                table.draw()
            }
        })
    })

</script>