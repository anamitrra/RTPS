<link rel="stylesheet" href="<?= base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.css") ?>" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
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
        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
            $("#submission_mode").val(clickedBtn);
            if (clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if (clickedBtn === 'SAVE') {
                var msg = "Do you want to procced";
            } else if (clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            } //End of if else            
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    if ((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                        $("#myfrm").submit();
                        $(".frmbtn").hide();
                    } else if (clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {} //End of if else
                }
            });
        });
    });
</script>
<style>
    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.2;
    }

    .topbar {
        width: 100%;
        text-align: justify;
        margin-top: 25px;
        line-height: 1.5;
    }

    .topbar p {
        font-size: 25px;
    }

    .data-text {
        font-size: 18px !important;
        font-family: Times New Roman, Times, serif;
        font-weight: bold;
    }

    .label-text {
        font-size: 18px !important;
        font-family: Times New Roman, Times, serif;
        font-style: italic;
        font-weight: 600;
    }

    table,
    td,
    th {
        border: 0px solid #595959;
        border-collapse: collapse;
    }

    td,
    th {
        padding: 3px;
        width: 30px;
        height: 25px;
    }

    th {
        background: #f0e6cc;
    }

    .even {
        background: #fbf8f0;
    }

    .odd {
        background: #fefcf9;
    }

    .curly-bracket {
        font-size: 20px;
        line-height: 1;
    }

    .border-td {
        border: 2px solid black;
        /* Set the desired border style */
    }

    .photo-td {
        width: 20%;
    }

    .passport-photo {
        width: 100px;
        height: 100px;
    }

    .border-design {
        border: 2px solid #000;
        border-radius: 10px;
        padding: 20px;
    }
</style>

<div class="container my-2" id="printDiv">
    <div class="card shadow-sm mt-2">
        <div class="card-body border-design">
            <form id="myfrm" method="POST" action="<?= base_url('spservices/upms/dscsign/withoutpdfsigned/') ?>" enctype="multipart/form-data">
                <input id="obj_id" name="obj_id" value="<?= $data[0]->_id->{'$id'} ?>" type="hidden" />
                <input id="certificate_no" name="certificate_no" value="<?= $certificate_no ?>" type="hidden" />
                <input id="certificate_path" name="certificate_path" value="<?= $certificate_path ?>" type="hidden" />
                <div class="topbar">
                    <table width="100%">
                        <tr>
                            <td style="width:35%">Form No Ex. - 31</td>
                            <td style="width:30%">
                                <div class="row" style="display: flex; justify-content: center; align-items: center;">
                                    <img class="img" src="<?= base_url("assets/frontend/images/logo_ahsec.png") ?>" width="80" height="80" />
                                </div>
                            </td>
                            <td style="width:35%;text-align: right;">SL. No. <b><?= $marksheet_data['Admit_Card_Serial_No'] ?></b></td>
                        </tr>
                    </table>
                    <p style="text-align:center;font-weight:bold;margin-top:0;padding-top:0;color:#af7077">ASSAM HIGHER SECONDARY EDUCATION COUNCIL : GUWAHATI - 21 <br>DUPLICATE ADMIT</p><br>
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td class="label-text">Name</td>
                                <td class="data-text"><?= $reg_data->candidate_name ?></td>
                                <td colspan="2"></td>
                                <td rowspan="5" class="photo-td">
                                <img src="<?= base_url("storage/docs/ahsec/photos/0" . $reg_data->photo_key_value . ".jpg") ?>" style="width:130px; height: 130px; margin: 3px;" /><br>
                                    <?php $qrcode = base64_encode(file_get_contents(FCPATH . $qr));
                                    ?>
                                    <img src="data:image/png;base64,<?php echo $qrcode ?>" class="passport-photo">
                                </td>
                            </tr>
                            <tr>
                                <td class="label-text">Son/ Daughter of</td>
                                <td class="data-text"><?= $reg_data->father_name ?><br> <?= $reg_data->mother_name ?></td>
                                <td class="label-text" colspan="2">&</td>
                            </tr>
                            <tr>
                                <td class="label-text">Roll</td>
                                <td class="data-text"><?= $marksheet_data['Roll'] ?></td>
                                <td class="label-text">No</td>
                                <td class="data-text"><?= $marksheet_data['No'] ?></td>
                            </tr>
                            <tr>
                                <td class="label-text">Registration No</td>
                                <td class="data-text"><?= $reg_data->registration_number ?></td>
                                <td class="label-text">Session</td>
                                <td class="data-text"><?= $reg_data->registration_session ?> to the</td>
                            </tr>
                            <tr>
                                <td class="label-text">Higher Secondary Final Examination,</td>
                                <td class="data-text"><?= $marksheet_data['Year_of_Examination'] ?></td>
                                <td class="label-text">Commensing on the </td>
                                <td class="data-text"><?php if (isset($marksheet_data['commencing_day']) && isset($marksheet_data['commencing_month'])) {
                                                            echo dayMonthToFormat($marksheet_data['commencing_day'], $marksheet_data['commencing_month']);
                                                        } ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table width="100%">
                        <tbody>
                            <tr>
                                <td rowspan="2">Note:- <br>(a) Subjects of Examination</td>
                                <td colspan="2" class="border-td">CORE SUBJECTS</td>
                                <td colspan="4" class="border-td">ELECTIVE SUBJECTS</td>
                                <td rowspan="3"></td>
                            </tr>
                            <tr>
                                <td class="border-td data-text"><b><?= $reg_data->sub_1 ?></b></td>
                                <td class="border-td data-text"><b><?= $reg_data->sub_2 ?></b></td>
                                <td class="border-td data-text"><b><?= $reg_data->sub_3 ?></b></td>
                                <td class="border-td data-text"><b><?= $reg_data->sub_4 ?></b></td>
                                <td class="border-td data-text"><b><?= $reg_data->sub_5 ?></b></td>
                                <td class="border-td data-text"><b><?= $reg_data->sub_6 ?></b></td>
                            </tr>
                            <tr>
                                <td>(b) Hours of Examination</td>
                                <td colspan="6">
                                    <div class="curly-bracket">
                                        <b>Morning: From 9:00 AM to 12:00 Noon</b></br>
                                        <b>Afternoon: From 1:30 PM to 04:30 PM</b></br>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="info" style="margin-top:5px;">
                    <p>N.B:- Any alteration made in the entries on this Admission Card without the authority of the Council renders the candidate liable for disqualification for sitting at this or any subsequent Examination.</p>
                </div>
                <div class="sign_div" style="margin-top:3px;">
                    <!-- <p style="text-align: right;">Yours Faithfully,</p> -->
                    <table width="100%">
                        <tr>
                            <td style="text-align:left;font-weight:bold">
                                <i>Counter Signature<i><br><br>Officer-in-Charge<br>Examination Center<br>(Office Seal)</p>
                            </td>
                            <td></td>
                            <td style="text-align:center;font-weight:bold">
                                <p>Sd/-<br>Controller of Examinations,<br>Assam Higher Secondary Education Council<br>Guwahati-21</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <img src="<?= base_url('assets/frontend/images/logo_ahsec.png') ?>" alt="Watermark" class="watermark">
        </div>
        <div class="card-footer text-center no-print">
            <?php if ($user_type == 'official') { ?>
                <a href="<?= base_url('spservices/upms/myapplications/process/' . $data[0]->_id->{'$id'}) ?>" class="btn btn-primary">
                    <i class="fa fa-angle-double-left"></i> BACK
                </a>
            <?php } ?>
            <button class="btn btn-warning" onclick="window.close();" type="button">
                <i class="fa fa-print"></i> CLOSE
            </button>
            <button class="btn btn-info" id="printBtn" type="button">
                <i class="fa fa-print"></i> PRINT
            </button>
            <?php if ($user_type == 'official') { ?>
                <button class="btn btn-success frmbtn" id="SAVE" type="button">
                    <i class="fa fa-check" aria-hidden="true"></i> DELIVER
                </button>
            <?php } ?>
            </from>
        </div>
    </div>
</div>

<?php
function dayMonthToFormat($day, $month)
{
    // Ensure day is within the valid range (1-31) and month is a valid month name
    if ($day < 1 || $day > 31 || !in_array(ucfirst(strtolower($month)), [
        'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
        'October', 'November', 'December'
    ])) {
        return "Invalid day or month.";
    }

    // Convert the day to the appropriate suffix (e.g., 1st, 2nd, 3rd, 4th, etc.)
    if ($day >= 11 && $day <= 13) {
        $day_suffix = 'th';
    } else if ($day % 10 === 1) {
        $day_suffix = 'st';
    } else if ($day % 10 === 2) {
        $day_suffix = 'nd';
    } else if ($day % 10 === 3) {
        $day_suffix = 'rd';
    } else {
        $day_suffix = 'th';
    }

    // Format the result string
    $result = "{$day}{$day_suffix} day of {$month}";
    return $result;
}

?>