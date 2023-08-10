<?php
$obj_id = $response->{'_id'}->{'$id'};

$unformat_date = format_mongo_date($response->form_data->submission_date); //dateformat helper
$txts = explode(' ', $unformat_date);
$sub_date = $txts[0];

$renewalDate = isset($response->form_data->renewal_date) ? $response->form_data->renewal_date : '';
if($renewalDate) {
$date_renewal = $response->form_data->renewal_date;
$unformat_renewdate = getDateFormat($date_renewal);
$txts = explode(' ', $unformat_renewdate);
$rnew_date = $txts[0];
$rn_ex = explode('-', $rnew_date);
}
else {
    $rnew_date = "--/--/----";
}

$fin_year = getFinYear();
$fy_ex = explode('-', $fin_year);
$valid_year_range = $fy_ex[0]."-".$rn_ex[2];

$applicant_details = "";
if($response->form_data->applicant_type == 'Individual')
{
    $applicant_name = $response->form_data->applicant_name;
    $guardian_name = "C/O: " . $response->form_data->father_husband_name;

    $house_ward_no = $response->form_data->communication_address->house_ward_no;
    $lane_road_loc = $response->form_data->communication_address->lane_road_loc;
    $vill_town_city = $response->form_data->communication_address->vill_town_city;
    $post_office = $response->form_data->communication_address->post_office;
    $pol_station = $response->form_data->communication_address->pol_station;
    $district = $response->form_data->communication_address->district;
    $pin_code = $response->form_data->communication_address->pin_code;

    $applicant_details = $applicant_name.",<br/>".$guardian_name;
} else {
    
    if($response->form_data->applicant_type == 'Proprietorship') {
        $applicant_name = "";
        $guardian_name = $response->form_data->owner_director_name;

        $applicant_details = $guardian_name;
    } else {
        $applicant_name = $response->form_data->org_name;
        $guardian_name = $response->form_data->owner_director_name;

        $applicant_details = $applicant_name.",<br/>".$guardian_name;
    }
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
$mobile = $response->form_data->mobile;
$address = $address."<br/> Mobile: ".$mobile;
if($response->form_data->deptt_name == 'PWDB') {
    if($category == 'Class-II')
    {
        $office_head = "OFFICE OF THE SUPERINTENDING ENGINEER";
        $signed_by = '<h10><b>Superintending Engineer, P.W.D.(Bldg)</b></h10><br>
        <b>' . $response->form_data->circle . '</b>';
    } 
    else if ($category == 'Class-1(A)') {
        $office_head = "OFFICE OF THE CHIEF ENGINEER";
        $signed_by = '<h10><b>Chief Engineer, P.W.D.(Bldg)</b></h10><br>
        <b>Assam, Chandmari, Guwahati-3</b>';
    } 
    else {
        $office_head = "OFFICE OF THE ADDL. CHIEF ENGINEER";
        $signed_by = '<h10><b>Addl. Chief Engineer, P.W.D.(Bldg)</b></h10><br>
        <b>Assam, Chandmari, Guwahati-3</b>';
    }
} 
else if ($response->form_data->deptt_name == 'PHED') {
    if ($category == 'Class-II') 
    {
        $office_head = "OFFICE OF THE SUPERINTENDING ENGINEER";
        $signed_by = '<h10><b>Superintending Engineer, PHE</b></h10><br>
        <b>' . $response->form_data->circle . '</b>';
    } 
    else if ($category == 'Class-1(A)') {
        $office_head = "OFFICE OF THE CHIEF ENGINEER";
        $signed_by = '<h10><b>Chief Engineer, PHE</b></h10><br>
        <b>Assam</b>';
    } 
    else {
        $office_head = "OFFICE OF THE ADDL. CHIEF ENGINEER";
        $signed_by = '<h10><b>Addl. Chief Engineer, PHE</b></h10><br>
        <b>' . $response->form_data->zone . '</b>';
    }
} 
else if ($response->form_data->deptt_name == 'WRD') {
    if ($category == 'Class-II') {
        $office_head = "OFFICE OF THE SUPERINTENDING ENGINEER";
        $signed_by = '<h10><b>Superintending Engineer, <br>Water Resources Department</b></h10><br>
        <b>' . $response->form_data->circle . '</b>';
    } else {
        $office_head = "OFFICE OF THE CHIEF ENGINEER";
        $signed_by = '<h10><b>Chief Engineer, <br>Water Resources Department,<br/> Basistha, Guwahati-29</b></h10>';
    }
}
else if ($response->form_data->deptt_name == 'GMC') {

        $office_head = "OFFICE OF THE CHIEF ENGINEER";
        $signed_by = '<h10><b>Chief Engineer, <br>Guwahati Municipal Corporation,<br/> Guwahati</b></h10>';

}
else if($response->form_data->deptt_name == 'PWDNH') {
    if($category == 'Class-II')
    {
        $office_head = "OFFICE OF THE SUPERINTENDING ENGINEER";
        $signed_by = '<h10><b>Superintending Engineer, PWD (NH Works)</b></h10><br>
        <b>' . $response->form_data->circle . '</b>';
    } 
    else {
        $office_head = "OFFICE OF THE CHIEF ENGINEER";
        $signed_by = '<h10><b>Chief Engineer, PWD (NH Works)</b></h10><br>
        <b>Assam, Chandmari, Guwahati-781003</b>';
    }
}


$params['data'] = "Regs. No.:" . $response->form_data->registration_no . "|PAN:" . $response->form_data->pan_card . "|id:" . $obj_id;
$params['level'] = 'H';
$params['size'] = 10;
$params['savename'] = 'storage/temps/' . $obj_id . '.png';
$this->ciqrcode->generate($params);
?>

<style>
    
    .print {
        visibility: visible;
    }

    table,td,th {
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
                <div class="d-flex" style="margin-bottom: 20px;">
                    <div style="font-size: 18px; font-weight: bold; text-transform: uppercase">GOVERNMENT OF ASSAM
                    </div>
                    <div style="font-size: 14px; font-weight: bold; margin: 5px auto"><?= $office_head?></div>
                    <div style="font-size: 14px; font-weight: bold;"><?= $deptt?> </div>
                        <?php if ($response->form_data->deptt_name == 'WRD') { ?>
                            <div style="font-size: 14px; font-weight: bold;">ASSAM WATER CENTRE :::: BASISTHA, GUWAHATI-29. <br>
                                Email Id: cewrd.assam@yahoo.co.in
                        </div>
                        <?php } ?>
                        <?php if ($response->form_data->deptt_name == 'PHED') { 
                            if($category == 'Class-II') {?>
                            <div style="font-size: 14px; font-weight: bold;"><?= $response->form_data->circle?>
                            </div>
                        <?php } else { ?>
                            <div style="font-size: 14px; font-weight: bold;"><?= $response->form_data->zone?>
                            </div>
                        <?php 
                        }
                    } ?>
                    <?php if ($response->form_data->deptt_name == 'PWDB') {
                        if($category == 'Class-II') {?>
                            <div style="font-size: 14px; font-weight: bold;"><?= $response->form_data->circle?></div>
                        <?php } else { ?>
                            <div style="font-size: 14px; font-weight: bold;">Chandmari, Guwahati-03</div>
                        <?php 
                        }
                    } ?>
                    <?php if ($response->form_data->deptt_name == 'PWDNH') {
                        if($category == 'Class-II') {?>
                            <div style="font-size: 14px; font-weight: bold;"><?= $response->form_data->circle?></div>
                        <?php } else { ?>
                            <div style="font-size: 14px; font-weight: bold;">Chandmari, Guwahati-03</div>
                        <?php 
                        }
                    } ?>
                    <div style="float: right;">
                    <?php $qrcode = base64_encode(file_get_contents(FCPATH . 'storage/temps/'.$obj_id.'.png'));
                            ?>
                    <img src="data:image/png;base64, '.<?php echo $qrcode ?>.'" style="width: 20mm; height: 20mm" >
                    <br><br>
                    </div>
                </div> 
                    <br><br><br>
                    <div style="float: left; border: none; font-weight: bold;">NO: <?= $regs_no?></div>
                    <div style="float: right; border: none; font-weight: bold;">DATED: <?= $sub_date?></div><br>

                    <div class="row" style="font-size: 14px;">
                        <p style="text-align: justify; line-height: 24px">
                            The following Contractor is hereby registered in <b><?=$category?></b> Category under <?= $deptt?> and
                            <b>New Registration</b> number is hereby alloted for the year <b><?php echo $valid_year_range;?></b>.
                            <br>
                            <?php if ($response->form_data->deptt_name == 'WRD') { ?>
                        <p style="text-align: left; line-height: 24px">The Registration is provisional and is liable for cancellation at any time under the following terms and conditions. <br>
                            1. In case of adverse Police Report.
                            <br>2. Performance of Unsatisfactory Works.
                            <br>3. Misappropriate / misuse / subletting of Govt. materials issued to him.
                            <br>4. Misconduct / misbehaviour towards departmental personal.
                            <br>5. Any violation of Govt. Rules, Regulation and norms.
                            <br>6. Any other matter that may cause untoward situation against Govt./Public.<br>
                            And is such cases the security money deposited will be forfeited to the Govt. and no claim will be entertained.
                        </p>
                    <?php } else { ?>
                        <p style="text-align: justify;">
                            The Registration is provisional and is liable for cancellation at any time in case of unsatisfactory
                            works and in the event of an adverse Police report.
                        </p>
                    <?php } ?>  
                         <p style="text-align: justify;">
                            The Registration number will remain valid up to <b><?= $rnew_date?></b> and thereafter fresh renewal 
                            number will have to be obtained by submitting an application
                         </p>


                        <table>
                            <tr>
                                <th>Registration No.</th>
                                <th>Name of the Contractor/ Firm/ Company</th>
                                <th>Address and Contact No.</th>
                                <th>GST No. & PAN</th>
                            </tr>
                            <tr style="height: 100px">
                                <td><?php echo $response->form_data->registration_no;?></td>
                                <td><p style="text-align: left;"><?php echo $applicant_details;?></p></td>
                                <td><p style="text-align: left;"><?php echo $address;?></p></td>
                                <td><?php echo $response->form_data->gst_no;?><br/><?php echo $response->form_data->pan_card;?></td>
                            </tr>
                        </table>
                    <div>

                    </div>

                    <div class="row" style="float: right;margin-top: 150px;">
                        <?php echo $signed_by;?>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
