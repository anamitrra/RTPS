<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.css">
<script src="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.js"></script>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-3">
            <div class="col-sm-10 mx-auto">
                <div class="card">
                    <div class="card-header">Download Report</div>
                    <div class="card-body py-1">
                        <form action="<?= base_url('spservices/office/report-generation') ?>" method="post" target="_blank">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Application Status</label>
                                        <select name="status" id="status" class="form-control form-control-sm">
                                            <option value="">Select Status</option>
                                            <option value="UNDER_PROCESSING">Under Processing</option>
                                            <option value="QUERY_ARISE">Query to Applicant</option>
                                            <option value="QUERY_SUBMITTED">Query Submitted</option>
                                            <option value="DELIVERED">Delivered</option>
                                            <option value="REJECTED">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Community</label>
                                        <select name="community" id="community" class="form-control form-control-sm">
                                            <option value="">Select Community</option>
                                            <option value="Muslim">Muslim</option>
                                            <option value="Christian">Christian</option>
                                            <option value="Sikh">Sikh</option>
                                            <option value="Buddhists">Buddhists</option>
                                            <option value="Zoroastrians(Parsi)">Zoroastrians(Parsi)</option>
                                            <option value="Jain">Jain</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Date range</label>
                                        <input id="date-range" class="form-control form-control-sm" type="text" name="daterange" value="" required />
                                        <input type="hidden" id="start_date" name="start_date" value="" />
                                        <input type="hidden" id="end_date" name="end_date" value="" />
                                        <!-- <input type="text" name="date_range" class="form-control form-control-sm" value="" /> -->
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="">&nbsp;</label>
                                    <p><button class="btn btn-danger btn-sm filter_btn"><i class="fa fa-search"></i> SEARCH</button>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        // $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
        //     $('#start_date').val("");
        //     $('#end_date').val("");
        //     $('input[name="daterange"]').val("");
        // });
        // $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
        //     $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
        //     $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
        // });
        // $("#clear").click(function() {
        //     $("select").val("");
        //     $("#filters").empty();
        // });
        // $('input[name="daterange"]').daterangepicker({
        //     opens: 'left',
        //     linkedCalendars: false,
        //     showDropdowns: true,
        //     startDate: moment().subtract(29, 'days'),
        //     endDate: moment(),
        //     locale: {
        //         "format": "DD/MM/YYYY",
        //         "separator": " - ",
        //         "applyLabel": "Apply Date Range",
        //         "cancelLabel": "Clear Selection",
        //         "fromLabel": "From",
        //         "toLabel": "To",
        //         "customRangeLabel": "Custom",
        //         "weekLabel": "W",
        //         "daysOfWeek": [
        //             "Su",
        //             "Mo",
        //             "Tu",
        //             "We",
        //             "Th",
        //             "Fr",
        //             "Sa"
        //         ],
        //         "monthNames": [
        //             "January",
        //             "February",
        //             "March",
        //             "April",
        //             "May",
        //             "June",
        //             "July",
        //             "August",
        //             "September",
        //             "October",
        //             "November",
        //             "December"
        //         ],
        //         "firstDay": 1
        //     },
        // }, function(start, end, label) {
        //     // console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        // });
        $('input[name="daterange"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
                        opens: 'left',
            linkedCalendars: false,
            showDropdowns: true,
        });

        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $('#start_date').val("");
            $('#end_date').val("");
        });


    });
</script>