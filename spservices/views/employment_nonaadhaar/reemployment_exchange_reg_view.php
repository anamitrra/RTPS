<?php

if ($dbrow) {
    $booking_date = isset($dbrow->form_data->booking_date) ? $dbrow->form_data->booking_date : set_value("booking_date");
    $time_slot = isset($dbrow->form_data->time_slot) ? $dbrow->form_data->time_slot : set_value("time_slot");
    $employment_exchange = isset($dbrow->form_data->employment_exchange) ? $dbrow->form_data->employment_exchange : set_value("employment_exchange");
} else {
    $booking_date = set_value('booking_date');
    $time_slot = set_value('time_slot');
    $employment_exchange = set_value('employment_exchange');
}
?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }

    ol li {
        font-size: 14px;
        font-weight: bold;
    }
</style>
<!-- <link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>"> -->
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<!-- <script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script> -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    var dates = ["20/04/2023", "22/04/2023"];

    function DisableDates(date) {
        var string = jQuery.datepicker.formatDate('dd/mm/yy', date);
        return [dates.indexOf(string) == -1];
    }

    $(function() {
        $(".dp").datepicker({
            beforeShowDay: DisableDates,
            dateFormat: 'mm/dd/yy',
        });
    });
    $(document).ready(function() {
        // $(".dp").datepicker({
        //     format: 'dd-mm-yyyy',
        //     startDate: '+0d',
        //     // endDate: '+0d',
        //     autoclose: true,
        // });

        $('.check_slot').on('click', function() {
            var bookingDate = $('#booking_date').val();
            var timeSlot = $('#time_slot').val();
            var office = $('#employment_exchange').val();
            var ref_no = $('#rtps_ref_no').val();
            if ((bookingDate.length == '') || (timeSlot.length == '') || (office.length == '')) {
                alert('Please select booking slot.')
            } else {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?= base_url('spservices/slotbooking/CheckAvailability') ?>",
                    data: {
                        "booking_date": bookingDate,
                        "time_slot": timeSlot,
                        "office": office,
                        "service": "EMP_REG_NA",
                        "ref_no": ref_no
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.status == 1) {
                            console.log('okkk')
                            $('#message').text(res.msg)
                            $('#message').removeClass('text-danger')
                            $('#message').addClass('text-success')
                        } else {
                            $('#message').text(res.msg)
                            $('#message').removeClass('text-success')
                            $('#message').addClass('text-danger')
                        } //End of if else
                    }
                });
            }
        })
    });
</script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/employment-reregistration-nonaadhaar/save-slot') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input id="rtps_ref_no" name="rtps_ref_no" value="<?= $dbrow->service_data->appl_ref_no ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Registration of employment seeker in Employment Exchange
                </div>
                <div class="card-body" style="padding:5px">
                    <?php if ($this->session->flashdata('fail') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('success') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>

                    <h5 class="text-center mt-3 text-success"><u><strong>Time Slot for Physical Document Verification</strong></u></h5>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Employment Exchange Office </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Select Date <span class="text-danger">*</span></label>
                                <input name="booking_date" class="form-control dp" type="text" value="<?= $booking_date ?>" id="booking_date" />
                                <?= form_error("booking_date") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Select Time Slot <span class="text-danger">*</span></label>
                                <select name="time_slot" class="form-control" id="time_slot">
                                    <option value="">Please Select</option>
                                    <?php foreach ($time_slots as $ts) { ?>
                                        <option value="<?= $ts->time_slot ?>" <?= ($ts->time_slot == $time_slot) ? "selected" : "" ?>><?= $ts->time_slot ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("time_slot") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Employment Exchange <span class="text-danger">*</span></label>
                                <select name="employment_exchange" class="form-control" id="employment_exchange">
                                    <option value="">Please Select</option>
                                    <?php
                                    foreach ($employment_office as $eo) { ?>
                                        <option value="<?= $eo->employment_exchange_office ?>" <?= ($eo->employment_exchange_office == $employment_exchange) ? 'selected' : '' ?>><?= $eo->employment_exchange_office ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("employment_exchange") ?>
                            </div>
                            <div class="col-md-6">
                                <p></p><br>
                                <button class="btn btn-sm btn-warning check_slot" type="button">Check Availability</button>
                                <p class="mt-1" id="message" style="font-weight:bold"></p>
                            </div>
                        </div>
                    </fieldset>

                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/employment-reregistration-nonaadhaar/work-experiences/' . $obj_id) ?>">
                        <i class="fa fa-angle-double-left"></i> Previous
                    </a>
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>