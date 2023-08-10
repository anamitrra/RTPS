
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.css">

<!-- DataTables -->
<script src="<?= base_url("assets/"); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.js"></script>

<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="content-header">
            <h3>Citizen Information Access Log</h3>
        </div>
        <div class="content">

            <div class="card">
                <div class="card-header">
                    <h6>Filter Report</h6>
                </div>
                <div class="card-body">
                    <form id="filter-citizen-form" action="#" method="POST">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <label for="date-range">Date Range</label>
                                <input id="date-range" class="form-control" type="text" name="daterange" value=""/>
                                <input type="hidden" id="start_date" name="start_date" value="" />
                                <input type="hidden" id="end_date" name="end_date" value="" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card card-body">
                <div class="table-responsive mt-3">
                    <table id="citizen-access-log-table" class="table table-bordered text-center">
                        <thead>
                        <tr>
                            <th class="align-top">#</th>
                            <th class="align-top">Name</th>
                            <th class="align-top">IP</th>
                            <th class="align-top">User Agent</th>
                            <th class="align-top">URI</th>
                            <th class="align-top">Method</th>
                            <th class="align-top">Accessed On</th>
                            <th class="align-top">Data</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="align-bottom">#</th>
                            <th class="align-bottom">Name</th>
                            <th class="align-bottom">IP</th>
                            <th class="align-bottom">User Agent</th>
                            <th class="align-bottom">URI</th>
                            <th class="align-bottom">Method</th>
                            <th class="align-bottom">Accessed On</th>
                            <th class="align-bottom">Data</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){

        const table = $('#citizen-access-log-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel',
                'pdf'
            ],
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
                }
            ],
            "columns": [
                {
                    "data": "#"
                },
                {
                    "data": "name"
                },
                {
                    "data": "ip"
                },
                {
                    "data": "user_agent"
                },
                {
                    "data": "uri"
                },
                {
                    "data": "method"
                },
                {
                    "data": "created_at"
                },
                {
                    "data": "data"
                }
            ],
            "ajax": {
                url: '<?php echo base_url("mis/citizen/get-access-log") ?>',
                type: 'POST',
                data: function(d){
                    d.startDate = $('#start_date').val();
                    d.endDate   = $('#end_date').val();
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

            table.draw();
        });
    })
</script>