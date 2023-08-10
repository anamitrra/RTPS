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
                    <h1 class="m-0 text-dark">RTPS SYSTEM DASHBOARD</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("mis"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Revenue Filter</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div><!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Revenue Filter</h3>
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
<!--                                <label for="date-range" class="col-sm-6 col-form-label text-right">Select Date Range:</label>-->
                                <input id="date-range" class="form-control" type="text" name="daterange" value="" required/>
                                <input type="hidden" id="start_date" name="start_date" value="" />
                                <input type="hidden" id="end_date" name="end_date" value="" />
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <select name="payment_mode" id="payment_mode" class="form-control" required>
                                    <option value="">Select a payment mode</option>
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                    <option value="both">Both</option>
                                </select>
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

            <div class="card" id="revenue-card">
                <div class="card-body">
                    <h2 class="card-title text-center font-weight-bold text-primary">Total revenue collected for the following applications : <span id="revenue-collected"></span> INR</h2>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="revenue-table" class="table table-bordered table-hover table-striped" style="width:100%">
                            <thead>
                            <tr class="table-header">
                                <th>#</th>
                                <th>Appl Ref No</th>
                                <th>Applicant</th>
                                <th>Sub Date</th>
                                <th>Sub Office</th>
                                <th>Payment Date</th>
                                <th>Amount</th>
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
    var revenueCollectedRef = $('#revenue-collected')
    var revenueCardRef = $('#revenue-card')

    $(document).ready(function() {
        revenueFilterFormRef.parsley();
        revenueCollectedRef.parent().hide()
        revenueCardRef.hide()

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
                    "data": "payment_date"
                },
                {
                    "data": "amount"
                }
            ],
            "ajax": {
                url: '<?php echo base_url("mis/applications/get_records_revenue_filter") ?>',
                type: 'POST',
                data: function(d) {
                    let deptId = $('#departments').val();
                    let serviceId = $('#services').val();
                    let startDate = $('#start_date').val();
                    let endDate = $('#end_date').val();
                    let paymentMode = $('#payment_mode').val();
                    d.deptId = deptId;
                    d.serviceId = serviceId;
                    d.startDate = startDate;
                    d.endDate = endDate;
                    d.paymentMode = paymentMode;
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
            let url = '<?= base_url("mis/get_services/") ?>' + deparment_id;
            $.get(url, function(data, status) {
                let res = JSON.parse(data)
                if (res) {
                    $('#services').empty();
                    $('#services').append(`<option value=""> Select Services</option>`);
                    res.forEach(function(item, index) {
                        $('#services').append(`<option value="${item.service_id}">
                                   ${item.service_name}
                                 </option>`);
                    });
                }
            })
        })
        revenueFilterFormRef.on('submit', function(e) {
            e.preventDefault();
            let deptId = $('#departments').val();
            let serviceId = $('#services').val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let paymentMode = $('#payment_mode').val();

            $.ajax({
                url: '<?=base_url('mis/applications/get-revenue-collected')?>',
                type: 'POST',
                dataType: 'json',
                data: {deptId, serviceId, startDate, endDate, paymentMode},
                success: function(response){
                    if(response.hasOwnProperty('revenueCollected')){
                        revenueCollectedRef.text(response.revenueCollected)
                        revenueCollectedRef.parent().show();
                        revenueCardRef.show();
                    }else{
                        revenueCollectedRef.parent().hide();
                        swal.fire('Fail','No data found','error')
                    }
                }
            })
        })

        revenueFilterBtnRef.on('click', function(e) {
            // alert("Hi")
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