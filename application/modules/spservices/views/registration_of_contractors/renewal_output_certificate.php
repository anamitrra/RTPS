<?php
$obj_id = $response->{'_id'}->{'$id'};

$unformat_date = format_mongo_date($response->form_data->submission_date); //dateformat helper
$txts = explode(' ', $unformat_date);
$sub_date = $txts[0];

$renewalDate = isset($response->form_data->renewal_date) ? $response->form_data->renewal_date : '';
if ($renewalDate) {
    $date_renewal = $response->form_data->renewal_date;
    $unformat_renewdate = getDateFormat($date_renewal);
    $txts = explode(' ', $unformat_renewdate);
    $rnew_date = $txts[0];
} else {
    $rnew_date = "--/--/----";
}

if ($response->form_data->applicant_type == 'Individual') {
    $applicant_name = $response->form_data->applicant_name;
    $guardian_name = "C/O: " . $response->form_data->father_husband_name;

    $house_ward_no = $response->form_data->communication_address->house_ward_no;
    $lane_road_loc = $response->form_data->communication_address->lane_road_loc;
    $vill_town_city = $response->form_data->communication_address->vill_town_city;
    $post_office = $response->form_data->communication_address->post_office;
    $pol_station = $response->form_data->communication_address->pol_station;
    $district = $response->form_data->communication_address->district;
    $pin_code = $response->form_data->communication_address->pin_code;
} else {
    $applicant_name = $response->form_data->org_name;
    $guardian_name = "";
    $house_ward_no = $response->form_data->regd_address->house_ward_no_ro;
    $lane_road_loc = $response->form_data->regd_address->lane_road_loc_ro;
    $vill_town_city = $response->form_data->regd_address->vill_town_city_ro;
    $post_office = $response->form_data->regd_address->post_office_ro;
    $pol_station = $response->form_data->regd_address->pol_station_ro;
    $district = $response->form_data->regd_address->district_ro;
    $pin_code = $response->form_data->regd_address->pin_code_ro;
}
$address = $house_ward_no . ", " . $lane_road_loc . ", " . $vill_town_city . ",<br/> PO. " . $post_office . ", PS. " . $pol_station . ",<br/> " . $district . ",<br/> PIN-" . $pin_code;
$category = $response->form_data->category;
$regs_no = $response->form_data->registration_no;
$deptt = $response->service_data->department_name;
if ($response->form_data->deptt_name == 'PWDB') {
    if ($category == 'Class-II') {
        $office_head = "OFFICE OF THE SUPERINTENDING ENGINEER";
        $signed_by = '<h10><b>Superintending Engineer, P.W.D.(Bldg)</b></h10><br>
        <b>' . $response->form_data->circle . '</b>';
    } else if ($category == 'Class-1(A)') {
        $office_head = "OFFICE OF THE CHIEF ENGINEER";
        $signed_by = '<h10><b>Chief Engineer, P.W.D.(Bldg)</b></h10><br>
        <b>Assam, Chandmari, Guwahati-3</b>';
    } else {
        $office_head = "OFFICE OF THE ADDL. CHIEF ENGINEER";
        $signed_by = '<h10><b>Addl. Chief Engineer, P.W.D.(Bldg)</b></h10><br>
        <b>Assam, Chandmari, Guwahati-3</b>';
    }
} else if ($response->form_data->deptt_name == 'PHED') {
    if ($category == 'Class-II') {
        $office_head = "OFFICE OF THE SUPERINTENDING ENGINEER";
        $signed_by = '<h10><b>Superintending Engineer, PHE</b></h10><br>
        <b>' . $response->form_data->circle . '</b>';
    } else if ($category == 'Class-1(A)') {
        $office_head = "OFFICE OF THE CHIEF ENGINEER";
        $signed_by = '<h10><b>Chief Engineer, PHE</b></h10><br>
        <b>Assam</b>';
    } else {
        $office_head = "OFFICE OF THE ADDL. CHIEF ENGINEER";
        $signed_by = '<h10><b>Addl. Chief Engineer, PHE</b></h10><br>
        <b>' . $response->form_data->zone . '</b>';
    }
} else if ($response->form_data->deptt_name == 'WRD') {
    if ($category == 'Class-II') {
        $office_head = "OFFICE OF THE SUPERINTENDING ENGINEER";
        $signed_by = '<h10><b>Superintending Engineer, WRD</b></h10><br>
        <b>' . $response->form_data->circle . '</b>';
    }
    else{
        $office_head = "OFFICE OF THE CHIEF ENGINEER";
        $signed_by = '<h10><b>Chief Engineer, WRD</b></h10><br>
            <b>' . $response->form_data->circle . '</b>';
    }
}



// $service_charge = isset($response->form_data->service_charge) ? $response->form_data->service_charge : 0;
// $no_printing_page = isset($response->form_data->no_printing_page)?$response->form_data->no_printing_page:0;
// $printing_charge_per_page = isset($response->form_data->printing_charge_per_page)?$response->form_data->printing_charge_per_page:0;
// $no_scanning_page = isset($response->form_data->no_scanning_page)?$response->form_data->no_scanning_page:0;
// $scanning_charge_per_page = isset($response->form_data->scanning_charge_per_page)?$response->form_data->scanning_charge_per_page:0;

// $params['data'] = base_url('spservices/registration_of_contractors/preview/' . $obj_id);
// $params['level'] = 'H';
// $params['size'] = 10;
// $params['savename'] = 'storage/temps/'.$obj_id.'.png';
// $this->ciqrcode->generate($params);
?>

<style>
    .print {
        visibility: visible;
    }

    table,
    td,
    th {
        border: 1px solid black;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 13px;
    }

    td {
        text-align: center;
    }
</style>

<div class="content-wrapper" style="text-align: center; float: center;height: 80%;">
    <div class="container mt-3 mb-3">
        <div class="card shadow">
            <div class="card-body">
                <div style="text-align: center; padding: 10px;">
                    <div style="font-size: 18px; font-weight: bold; text-transform: uppercase">GOVERNMENT OF ASSAM
                    </div>
                    <div style="font-size: 14px; font-weight: bold; margin: 5px auto"><?= $office_head ?></div>
                    <div style="font-size: 14px; font-weight: bold;"><?= $deptt ?> </div><br><br><br>

                    <div style="float: left; border: none; font-weight: bold;">NO: <?= $regs_no ?></div>
                    <div style="float: right; border: none; font-weight: bold;">DATED: <?= $sub_date ?></div><br>

                    <div class="row" style="font-size: 14px;">
                        <p style="text-align: justify; line-height: 24px">
                            The following Contractor is here by registered in <b><?= $category ?></b> Category under <?= $deptt ?> is here by allotted
                            <b>Renewal</b> number against the name for the year <b><?php echo getFinYear(); ?></b>.
                            <br>
                        <p style="text-align: justify;">
                            The Registration is provisional and is liable for cancellation at any time in case of unsatisfactory
                            works and in the event of adverse Police report.
                        </p>
                        <p style="text-align: justify;">
                            The Registration number will remain valid up to <b><?= $rnew_date ?></b> and thereafter fresh renewal
                            number will have to be obtained by submitting application
                        </p>


                        <table>
                            <tr>
                                <th>Registration No.</th>
                                <th>Name of the Contractor/ Firm/ Company</th>
                                <th>Address and Contact No.</th>
                                <th>GST No. & PAN</th>
                            </tr>
                            <tr style="height: 100px">
                                <td><?php echo $response->form_data->registration_no; ?></td>
                                <td>
                                    <p style="text-align: left;"><?php echo $applicant_name;
                                                                    echo "<br/>" . $guardian_name; ?></p>
                                </td>
                                <td>
                                    <p style="text-align: left;"><?php echo $address; ?></p>
                                </td>
                                <td><?php echo $response->form_data->gst_no; ?><br /><?php echo $response->form_data->pan_card; ?></td>
                            </tr>
                        </table>
                        <div>

                        </div>
                        </br>
                        <div class="row" style="float: right;margin-top: 330px;">
                            <?php echo $signed_by; ?>
                            <!-- <h10><b>Chief Engineer, P.W.D.(Bldg)</b></h10><br>
                                         <b>Assam, Chandmari, Guwahati-3</b> -->

                        </div>
                        </br>
                        <div class="row">
                        </div> <br>
                        <!-- <div class="row">
                        <p style="text-align:justify; line-height: 16px">
                            <br>
                            <b><u>Copy forwarded to the</u>:-</b><br>
                            <p style="text-align:left; line-height: 16pt;">
                                1. The Superintending Engineer, PWD, Guwahati Bldg. Circle-II, Guwahati-3 for information. <br>
                                2. The Executive Engineer, PWD, Capital Construction Division, Dispur, Guwahati-6 for information. <br>
                                3. Person concerned, Sri Jayan kr. Deori, S/O: - Sri M. Deori, Lakhiminagar, Main Road, P.0 + P.S: - Dispur, Guwahati-781006 for information. <br>
                                4. Computer Asstt. for entering data in the computer as per enclosed "Contractors/Vendor's Registration" form submitted by the Contractor. <br>
                            </p>
                        </p>
                    </div> -->
                        <!--<br><br><br><br><br>
                     <div class="row" style="float: right;>

                        <h10 class="h10"><b>Chief Engineer, P.W.D.(Bldg)</b></h10><br>
                                         <b>Assam, Chandmari, Guwahati-3</b>
                    </div><br><br><br> -->

                    </div>
                </div>
            </div>
        </div>
    </div>