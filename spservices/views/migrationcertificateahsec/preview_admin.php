<?php
//var_dump($dbrow);
//exit();
$obj_id = $dbrow->{'_id'}->{'$id'};
$pageTitleId = $dbrow->service_data->service_id;

// Master Data - Registration Details
$sl_no = $master_data->sl_no ?? '';
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
                <?php echo $pageTitle ?><br>
                ( <?php echo $PageTiteAssamese ?> )
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
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
                        </table>
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