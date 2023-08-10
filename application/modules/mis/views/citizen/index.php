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

<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="content-header">
            <h3>Citizen Information </h3>
        </div>
        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h6>Filter Report</h6>
                </div>
                <div class="card-body">
                    <form id="filter-citizen-form" action="#" method="POST">
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <label for="date-range">Date Range</label>
                                <input id="date-range" class="form-control" type="text" name="daterange" value=""/>
                                <input type="hidden" id="start_date" name="start_date" value="" />
                                <input type="hidden" id="end_date" name="end_date" value="" />
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="submission_location">Submission Location</label>
                                <select name="submission_location" id="submission_location" class="form-control select2">
                                    <?php
                                    if(isset($locationList)){
                                        ?>
                                        <option value="" hidden>Please select a location</option>
                                        <?php
                                        foreach ($locationList as $location){
                                            ?>
                                            <option value="<?=$location->location_name?>"><?=$location->location_name?></option>
                                            <?php
                                        }
                                    }else{
                                        ?>
                                        <option value="" hidden>No location found</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender" class="form-control select2" data-parsley-errors-container="#gender-error-container">
                                    <option value="" hidden>Please select a gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                    <option value="Unknown">Unknown</option>
                                </select>
                                <span id="gender-error-container"></span>
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="application_status">Application Status</label>
                                <select name="application_status" id="application_status" class="form-control select2" data-parsley-errors-container="#application-status-error-container">
                                    <option value="" hidden>Please select a status</option>
                                    <!--                                    <option value="Recommend">Recommended</option>-->
                                    <!--                                    <option value="Forward">Forwarded</option>-->
                                    <option value="under_process">Under Process</option>
                                    <option value="Reject">Rejected</option>
                                    <option value="Deliver">Delivered</option>
                                    <!--                                    <option value="Partial Deliver">Partial Delivered</option>-->
                                    <!--                                    <option value="Not Recommend">Not Recommended</option>-->
                                    <!--                                    <option value="Partial Reject">Partial Rejected</option>-->
                                    <!--                                    <option value="Hold">Hold</option>-->
                                    <!--                                    <option value="Re-Appeal">Re-Appealed</option>-->
                                    <!--                                    <option value="Return to Edit Application">Return to Edit Application</option>-->
                                    <!--                                    <option value="Transfered">Transferred</option>-->
                                </select>
                                <span id="application-status-error-container"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-3">
                                <label for="gender">Mode of Application</label>
                                <select name="mode_of_application" id="mode_of_application" class="form-control select2" data-parsley-errors-container="#gender-error-container">
                                    <option value="" hidden>Please select a mode</option>
                                    <option value="kiosk">PFC/CSC</option>
                                    <option value="in person">Concerned Office</option>
                                    <option value="online">Self</option>
                                </select>
                                <span id="gender-error-container"></span>
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="submission_location">Services</label>
                                <select name="service_id" id="service_id" class="form-control select2">
                                <?php
                                    if(isset($serviceList)){
                                        ?>
                                        <option value="" hidden>Please select a service</option>
                                        <?php
                                        foreach ($serviceList as $service){
                                            ?>
                                            <option value="<?=$service->service_id?>"><?=$service->service_name?></option>
                                            <?php
                                        }
                                    }else{
                                        ?>
                                        <option value="" hidden>No Service found</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <p class="mt-2 mb-0"><span class="text-danger">*</span> <span class="small text-muted font-weight-bold">Apply at least one filter</span></p>
                        <button class="btn btn-outline-primary btn-block font-weight-bold mt-3" id="citizen-filter-btn">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card card-body">
                <div class="row">
                    <div class="col-12">
                        <button type="button" onclick="applyFilterAndDownload()" class=" btn btn-outline-success btn-sm float-right">Download Excel</button>
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table id="citizen-data-table" class="table table-bordered text-center">
                        <thead>
                        <tr>
                            <th class="align-top">#</th>
                            <th class="align-top">Name <br> of the applicant</th>
                            <th class="align-top">Gender</th>
                            <th class="align-top">Phone Number</th>
                            <th class="align-top">Email ID</th>
                            <th class="align-top">Name <br> of service Applied</th>
                            <th class="align-top">Date <br> of application</th>
                            <th class="align-top">Mode <br> of application</th>
                            <th class="align-top">Status <br> of the application</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="align-bottom">#</th>
                            <th class="align-bottom">Name <br> of the applicant</th>
                            <th class="align-bottom">Gender</th>
                            <th class="align-bottom">Phone Number</th>
                            <th class="align-bottom">Email ID</th>
                            <th class="align-bottom">Name <br> of service Applied</th>
                            <th class="align-bottom">Date <br> of application</th>
                            <th class="align-bottom">Mode <br> of application</th>
                            <th class="align-bottom">Status <br> of the application</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var citizenFilterBtnRef = $('#citizen-filter-btn')
    var citizenFilterFormRef = $('#filter-citizen-form')
    var downloadExcelUrl = '<?=base_url('mis/citizen/download-excel')?>';
    var def1
    $(function(){
        $('.select2').select2();
        citizenFilterFormRef.parsley();
        const table = $('#citizen-data-table').DataTable({
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
                    "data": "#"
                },
                {
                    "data": "applicant_name"
                },
                {
                    "data": "applicant_gender"
                },
                {
                    "data": "mobile_number"
                },
                {
                    "data": "email"
                },
                {
                    "data": "service_name"
                },
                {
                    "data": "submission_date"
                },
                {
                    "data": "mode_of_application"
                },
                {
                    "data": "application_status"
                }
            ],
            "ajax": {
                url: '<?php echo base_url("mis/citizen/get-records") ?>',
                type: 'POST',
                data: function (d){
                    d.startDate = $('#start_date').val();
                    d.endDate = $('#end_date').val();
                    d.submission_location = $('#submission_location').val();
                    d.gender = $('#gender').val();
                    d.application_status = $('#application_status').val();
                    d.mode_of_application = $('#mode_of_application').val();
                    d.service_id = $('#service_id').val();
                },
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');

                    if (def1 !== undefined) {
                        def1.resolve();
                    }
                    Swal.close()
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
            "maxSpan": {
                    "days": 30
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

        citizenFilterBtnRef.on('click', function(e) {
            e.preventDefault()
            if (citizenFilterFormRef.parsley().validate()) {
                table.draw();
            }
        })
    })

    function applyFilterAndDownload(){
        def1 =  $.Deferred();
        Swal.fire({
            title: 'Loading!',
            html: 'Please Wait.',
            showConfirmButton:false,
            didOpen: () => {
                Swal.showLoading()
            },
            // willClose: () => {
            //     clearInterval(timerInterval)
            // }
        })
        citizenFilterBtnRef.trigger('click', def1)

        $.when(def1).done(function () {
            window.open(downloadExcelUrl,'_blank')
        })
        // Swal.close()
        // $(document).ajaxStop(function () {
        //     window.location.href = downloadExcelUrl
        //     // setTimeout(function () {
        //     //     window.location.href = downloadExcelUrl
        //     // }, 3000)
        // });
    }
</script>