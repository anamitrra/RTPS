<?php
//var_dump($dbrow);
//exit();
$obj_id = $dbrow->{'_id'}->{'$id'};
$pageTitleId = $dbrow->service_data->service_id;

if ($dbrow->service_data->service_id != "AHSECCMRK") {
    // Master Data - Registration Details
    $sl_no = "";
    if ($dbrow->service_data->service_id == "AHSECCRC") {
        $sl_no = $master_data->sl_no ?? '';
    } else if ($dbrow->service_data->service_id == "AHSECCADM") {
        $sl_no = $master_data->Admit_Card_Serial_No ?? '';
    } else if ($dbrow->service_data->service_id == "AHSECCPC") {
        $sl_no = $master_data->Certificate_Serial_No ?? '';
    }

    $institution_code = $master_data->institution_code ?? '';
    $candidate_name = $master_data->candidate_name ?? '';
    $father_name = $master_data->father_name ?? '';
    $mother_name = $master_data->mother_name ?? '';
    $gender = $master_data->gender ?? '';
    $institution_name = $master_data->institution_name ?? '';
    $registration_number = $master_data->registration_number ?? '';
    $registration_session = $master_data->registration_session ?? '';
    $sub_1 = $master_data->sub_1 ?? '';
    $sub_2 = $master_data->sub_2 ?? '';
    $sub_3 = $master_data->sub_3 ?? '';
    $sub_4 = $master_data->sub_4 ?? '';
    $sub_5 = $master_data->sub_5 ?? '';
    $sub_6 = $master_data->sub_6 ?? '';
    $mobile_no = $master_data->mobile_no ?? '';
    $issue_date = $master_data->issue_date ?? '';
    $new_issued_date = $master_data->new_issued_date ?? '';
    // End /////////

    //result table data
    $roll = $master_data->Roll ?? null;
    $no = $master_data->No ?? null;
    $stream = $master_data->Stream ?? null;
    $commencing_day = $master_data->commencing_day ?? null;
    $commencing_month = $master_data->commencing_month ?? null;
    $exam_year = $master_data->Year_of_Examination ?? null;
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

td {
    font-size: 14px;
}
</style>
<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<script src="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.js')?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(document).on("click", "#printBtn", function() {
        $("#printDiv").print({
            addGlobalStyles: true,
            stylesheet: null,
            rejectWindow: true,
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: null
        });
    });
});
</script>

<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv">
            <div class="card-header"
                style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                <?php
                switch ($pageTitleId) {
                    case "AHSECCRC":
                        echo 'Registration Card Details';
                        break;
                    case "AHSECCADM":
                        echo 'Admit Card Details';
                        break;
                    case "AHSECCMRK":
                        echo 'Marksheet Details';
                        break;
                    case "AHSECCPC":
                        echo 'Pass Certificate Details';
                        break;
                }
                ?>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($dbrow->service_data->service_id == "AHSECCRC" || $dbrow->service_data->service_id == "AHSECCADM" || $dbrow->service_data->service_id == "AHSECCPC") {?>
                        <table class="table table-bordered table-striped" style="overflow: scroll;">
                            <tr>
                                <th>Sl. No</th>
                                <td><?=$sl_no?></td>
                            </tr>
                            <tr>
                                <th>Institution Code</th>
                                <td><?=$institution_code?></td>
                            </tr>
                            <tr>
                                <th>Candidate Name</th>
                                <td><?=$candidate_name?></td>
                            </tr>
                            <tr>
                                <th>Father's Name</th>
                                <td><?=$father_name?></td>
                            </tr>
                            <tr>
                                <th>Mother's Name</th>
                                <td><?=$mother_name?></td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td><?=$gender?></td>
                            </tr>
                            <tr>
                                <th>Institution Name</th>
                                <td><?=$institution_name?></td>
                            </tr>
                            <tr>
                                <th>Registration Number</th>
                                <td><?=$registration_number?></td>
                            </tr>
                            <tr>
                                <th>Registration Session</th>
                                <td><?=$registration_session?></td>
                            </tr>
                            <tr>
                                <td>Subject Code 1</td>
                                <td><?=$sub_1?></td>
                            </tr>
                            <tr>
                                <td>Subject Code 2</td>
                                <td><?=$sub_2?></td>
                            </tr>
                            <tr>
                                <td>Subject Code 3</td>
                                <td><?=$sub_3?></td>
                            </tr>
                            <tr>
                                <td>Subject Code 4</td>
                                <td><?=$sub_4?></td>
                            </tr>
                            <tr>
                                <td>Subject Code 5</td>
                                <td><?=$sub_5?></td>
                            </tr>
                            <tr>
                                <td>Subject Code 6</td>
                                <td><?=$sub_6?></td>
                            </tr>
                            <tr>
                                <th>Mobile No</th>
                                <td><?=$mobile_no?></td>
                            </tr>
                            <tr>
                                <th>Registration Date</th>
                                <td><?=$issue_date?></td>
                            </tr>
                            <tr>
                                <th>Issued Date</th>
                                <td><?=$new_issued_date?></td>
                            </tr>
                            <?php if ($dbrow->service_data->service_id != "AHSECCRC") {?>
                            <tr>
                                <th>Stream</th>
                                <td><?=$stream?></td>
                            </tr>
                            <tr>
                                <th>Roll</th>
                                <td><?=$roll?></td>
                            </tr>
                            <tr>
                                <th>No</th>
                                <td><?=$no?></td>
                            </tr>
                            <tr>
                                <th>Commencing Day</th>
                                <td><?=$commencing_day?></td>
                            </tr>
                            <tr>
                                <th>Commencing Month</th>
                                <td><?=$commencing_month?></td>
                            </tr>
                            <tr>
                                <th>Exam Year</th>
                                <td><?=$exam_year?></td>
                            </tr>
                            <?php }?>
                        </table>
                        <?php }?>

                        <?php if ($dbrow->service_data->service_id == "AHSECCMRK") {?>
                        <table class="table table-bordered table-striped" style="overflow: scroll;">
                            <tr>
                                <th>Mark Sheet No</th>
                                <td><?=$master_data['Mark_Sheet_No']?></td>
                            </tr>
                            <tr>
                                <th>Stream</th>
                                <td><?=$master_data['Stream']?></td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td><?=$master_data['Date']?></td>
                            </tr>
                            <tr>
                                <th>Candidate Name</th>
                                <td><?=$master_data['Candidate_Name']?></td>
                            </tr>
                            <tr>
                                <th>School Name</th>
                                <td><?=$master_data['School_Name']?></td>
                            </tr>
                            <tr>
                                <th>Roll</th>
                                <td><?=$master_data['Roll']?></td>
                            </tr>
                            <tr>
                                <th>No</th>
                                <td><?=$master_data['No']?></td>
                            </tr>
                            <tr>
                                <th>Year of Examination</th>
                                <td><?=$master_data['Year_of_Examination']?></td>
                            </tr>
                            <tr>
                                <td>Registration No</td>
                                <td><?=$master_data['Registration_No']?></td>
                            </tr>
                            <tr>
                                <td>Registration Session</td>
                                <td><?=$master_data['Registration_Session']?></td>
                            </tr>
                            <tr>
                                <td>Remarks1</td>
                                <td><?=$master_data['Remarks1']?></td>
                            </tr>
                        </table>
                        
                        <table class="table table-bordered table-striped" style="overflow: scroll;">
                            <tr>
                                <th>Sub Code</th>
                                <th>Sub Pap_Indicator</th>
                                <th>Sub Name</th>
                                <th>Sub Theory Marks</th>
                                <th>Sub Practical Marks</th>
                                <th>Sub Grace Marks</th>
                                <th>Sub Total Marks</th>
                            </tr>
                            <?php if($master_data['total_sub'] > 0){ for ($i=1; $i <= $master_data['total_sub']; $i++) { ?>
                            <tr>
                                <td>
                                    <?= $master_data['Sub'.$i.'_Code'] ?>
                                </td>
                                <td>
                                    <?= $master_data['Sub'.$i.'_Pap_Indicator'] ?>
                                </td>
                                <td>
                                    <?= $master_data['Sub'.$i.'_Name'] ?>
                                </td>
                                <td>
                                    <?= $master_data['Sub'.$i.'_Th_Marks'] ?>
                                </td>
                                <td>
                                    <?= $master_data['Sub'.$i.'_Pr_Marks'] ?>
                                </td>
                                <td>
                                    <?= $master_data['Sub'.$i.'_Grace_Marks'] ?>
                                </td>
                                <td>
                                    <?= $master_data['Sub'.$i.'_Tot_Marks'] ?>
                                </td>
                            </tr>
                            <?php }} ?> 
                        </table>

                        <table class="table table-bordered table-striped" style="overflow: scroll;">
                            <tr>
                                <th>Total Marks in Words</th>
                                <td><?=$master_data['Total_Marks_in_Words']?></td>
                            </tr>
                            <tr>
                                <th>Total Marks in Figure</th>
                                <td><?=$master_data['Total_Marks_in_Figure']?></td>
                            </tr>
                            <tr>
                                <th>Total Grace in Figure</th>
                                <td><?=$master_data['Total_Grace_in_Figure']?></td>
                            </tr>
                            <tr>
                                <th>Result</th>
                                <td><?=$master_data['Result']?></td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <td><?=$master_data['Remarks2']?></td>
                            </tr>
                            <tr>
                                <th>ENVE Grade</th>
                                <td><?=$master_data['ENVE_Grade']?></td>
                            </tr>
                            <tr>
                                <th>Core Indicator</th>
                                <td><?=$master_data['Core_Indicator']?></td>
                            </tr>
                        </table>

                        <?php }?>
                    </div>
                </div>
            </div>

            <div class="card-footer text-center no-print">
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>