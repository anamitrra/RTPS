<?php
$time_slot = isset($dbrow->form_data->time_slot) ? $dbrow->form_data->time_slot : set_value("time_slot");
$booking_date = $dbrow->form_data->booking_date ?? '';
$office = $dbrow->form_data->employment_exchange ?? '';
$rtps_ref_no = $dbrow->service_data->appl_ref_no ?? '';
$holidays = $this->mongo_db->where(['year' => date('Y')])->get('holiday_master');
$holiday_list = array();
foreach ($holidays as $hd) {
    $date = new DateTime($hd->date);
    $holiday_list[] = $date->format('j-n-Y');
}
// $data['disabled_date'] = json_encode(['5-5-2023', '10-5-2023', '15-5-2023']);
$disabled_dates = json_encode($holiday_list); //echo "Hello"; pre($disabled_dates);
?>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-ui-1.13.2/jquery-ui.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-ui-1.13.2/jquery-ui.css') ?>">
<script type="text/javascript">
    $(document).ready(function() {
        var unavailableDates = <?= $disabled_dates ?>;

        function noSundaysOrHolidays(date) {
            var day = date.getDay();
            if (day != 0) {
                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();
                for (i = 0; i < unavailableDates.length; i++) {
                    if ($.inArray((d) + '-' + (m + 1) + '-' + y, unavailableDates) != -1) {
                        return [false];
                    }
                }
                return [true];
            } else {
                return [day != 0, ''];
            } //End of if else
        } //End of noSundaysOrHolidays()

        function addDays(theDate, days) {
            return new Date(theDate.getTime() + days * 24 * 60 * 60 * 1000);
        } //End of addDays()

        $(function() {
            var today = new Date();
            var maxDate = addDays(new Date(), 30);
            $(".dp").datepicker({
                dateFormat: 'dd/mm/yy',
                autoclose: true,
                minDate: today,
                maxDate: maxDate,
                beforeShowDay: noSundaysOrHolidays,
                todayHighlight: 'TRUE'
            });
        });

        $('.check_slot').on('click', function() {
            var bookingDate = $('#booking_date').val();
            var timeSlot = $('#time_slot').val();
            var office = $('#employment_exchange').val();
            var ref_no = $('#rtps_ref_no').val();
            if ((bookingDate.length == '') || (timeSlot.length == '') || (office.length == '')) {
                alert('Please select booking slot.');
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
                        // console.log(res);
                        if (res.status == 1) {
                            $('#message').text(res.msg);
                            $('#message').removeClass('text-danger');
                            $('#message').addClass('text-success');
                        } else {
                            $('#message').text(res.msg);
                            $('#booking_date').val('');
                            $('#time_slot').val('');
                            $('#message').removeClass('text-success');
                            $('#message').addClass('text-danger');
                        } //End of if else
                    }
                });
            }
        });
    });
</script>
<div class="row form-group">
    <div class="col-md-5">
        <label>Select Date <span class="text-danger">*</span></label>
        <input name="booking_date" class="form-control dp" type="text" value="<?= $booking_date ?>" id="booking_date" />
        <?= form_error("booking_date") ?>
    </div>
    <div class="col-md-5">
        <label>Select Time Slot <span class="text-danger">*</span></label>
        <select name="time_slot" class="form-control" id="time_slot">
            <option value="">Please Select</option>
            <?php
            $time_slots = $this->mongo_db->order_by(array('slot_id' => 'ASC'))->get('timeslot_master');
            foreach ($time_slots as $ts) { ?>
                <option value="<?= $ts->time_slot ?>" <?= ($ts->time_slot == $time_slot) ? "selected" : "" ?>><?= $ts->time_slot ?></option>
            <?php } ?>
        </select>
        <?= form_error("time_slot") ?>
    </div>
    <input type="hidden" name="" value="<?= $office ?>" id="employment_exchange">
    <input type="hidden" name="" value="<?= $rtps_ref_no ?>" id="rtps_ref_no">

    <div class="col-md-2" style="margin-top: 35px;">
        <button type="button" class="btn btn-sm btn-primary check_slot">Check Availability</button>
        <p id="message"></p>
    </div>
</div>