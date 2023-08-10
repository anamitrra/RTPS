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

    .custom-text {
        font-family: Arial, sans-serif;
        /* Set the default font family */
        font-size: 20px;
        /* Set the default font size */
        color: #333;
        /* Set the default text color */
        line-height: 1.5;
        /* Set the default line height */
    }
</style>

<div class="container my-2" id="printDiv">
    <div class="card shadow-sm mt-2">
        <div class="card-body">
            <form id="myfrm" method="POST" action="<?= base_url('spservices/upms/dscsign/withoutpdfsigned/') ?>" enctype="multipart/form-data">
                <input id="obj_id" name="obj_id" value="<?= $data[0]->_id->{'$id'} ?>" type="hidden" />
                <input id="certificate_no" name="certificate_no" value="<?= $certificate_no ?>" type="hidden" />
                <input id="certificate_path" name="certificate_path" value="<?= $certificate_path ?>" type="hidden" />
                <div class="topbar">
                    <font style="float: right;">Form No. Ex-32(a)</font>
                    <table width="100%">
                        <tr>
                            <td style="width:2%">
                                <img class="img" src="<?= base_url("assets/frontend/images/logo_ahsec.png") ?>" width="80" height="80" style="float: left; margin-left: 20px;" />
                            </td>
                            <td style="width:98%">
                                <div style="text-align: center;">
                                    <font style="font-family: Times New Roman, Times, serif;font-weight:bold;margin-top:0;padding-top:0; color: #6a2d33;font-size: 23px;">ASSAM HIGHER SECONDARY EDUCATION COUNCIL</font><br>
                                    <font style="font-family: Times New Roman, Times, serif;font-weight:bold;margin-top:0;padding-top:0; color: #6a2d33;font-size: 23px;">GUWAHATI - 781021</font><br>
                                    <font style="font-family: Times New Roman, Times, serif;font-weight:bold;margin-top:0;padding-top:0; color: #6a2d33;font-size: 23px;">MARKSHEET</font><br>
                                    <font style="font-family: Times New Roman, Times, serif;font-weight:bold;margin-top:0;padding-top:0; color: #6a2d33;font-size: 23px;">HIGHER SECONDARY FINAL EXAMINATION</font>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div style="display: flex; margin-top: 50px;">
                        <div style="width: 70%">
                            <table width="100%">
                                <tr>
                                    <td style="width:25%">
                                        <font style="margin-top:0;padding-top:0; color: #6a2d33;font-size: 17px;">Ms. No. : <?= $marksheet_data['Mark_Sheet_No'] ?></font>
                                    </td>
                                    <td style="width:25%">
                                        <font style="margin-top:0;padding-top:0; color: #6a2d33;font-size: 17px;">Stream : <?= $marksheet_data['Stream'] ?></font>
                                    </td>
                                    <td style="width:25%" colspan="2">
                                        <font style="margin-top:0;padding-top:0; color: #6a2d33;font-size: 17px;">Date : <?= $marksheet_data['Date'] ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:25%" colspan="4">
                                        <font style="margin-top:0;padding-top:0; color: #6a2d33;font-size: 17px;">Name : <?= $marksheet_data['Candidate_Name'] ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:25%" colspan="4">
                                        <font style="margin-top:0;padding-top:0; color: #6a2d33;font-size: 17px;">College/School : <?= $marksheet_data['School_Name'] ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:25%">
                                        <font style="margin-top:0;padding-top:0; color: #6a2d33;font-size: 17px;">Roll : <?= $marksheet_data['Roll'] ?></font>
                                    </td>
                                    <td style="width:25%" colspan="3">
                                        <font style="margin-top:0;padding-top:0; color: #6a2d33;font-size: 17px;">No. : <?= $marksheet_data['No'] ?></font>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="width: 30%;">
                            <img class="img" src="<?= base_url($data[0]->form_data->photo_of_the_candidate) ?>" width="130" style="float: right; margin-right: 30px;" />
                        </div>
                    </div>
                    <table width="100%" style="margin-top: 30px; border-color: #6a2d33; border-width: 2px; padding: 15px;" border="1">
                        <tr>
                            <td style="border-width: 2px; text-align: center; font-weight: 600;">
                                CODE
                            </td>
                            <td style="border-width: 2px; text-align: center; font-weight: 600;" colspan="2">
                                SUBJECTS
                            </td>
                            <td style="border-width: 2px; text-align: center; font-weight: 600;">
                                THEORY
                            </td>
                            <td style="border-width: 2px; text-align: center; font-weight: 600;">
                                PR
                            </td>
                            <td style="border-width: 2px; text-align: center; font-weight: 600;">
                                TOTAL
                            </td>
                            <td style="border-width: 2px; text-align: center; font-weight: 600;">
                                RESULT
                            </td>
                        </tr>
                        
                        <?php if($marksheet_data['total_sub'] > 0){ for ($i=1; $i <= $marksheet_data['total_sub']; $i++) { ?>
                        <tr>
                            <td style="<?php ($i < $marksheet_data['total_sub'])? print 'border-bottom-style: hidden;': ''; ?>border-width: 2px; text-align: center; padding: 8px; width: 10%;">
                                <?= $marksheet_data['Sub'.$i.'_Code'] ?>
                            </td>
                            <td style="<?php ($i < $marksheet_data['total_sub'])? print 'border-bottom-style: hidden;': ''; ?>border-width: 2px; text-align: center; padding: 8px; width: 7%">
                                <?= $marksheet_data['Sub'.$i.'_Pap_Indicator'] ?>
                            </td>
                            <td style="<?php ($i < $marksheet_data['total_sub'])? print 'border-bottom-style: hidden;': ''; ?>border-width: 2px; width: 30%">
                                <?= $marksheet_data['Sub'.$i.'_Name'] ?>
                            </td>
                            <td style="<?php ($i < $marksheet_data['total_sub'])? print 'border-bottom-style: hidden;': ''; ?>border-width: 2px; text-align: center; padding: 8px; width: 10%;">
                                <?= $marksheet_data['Sub'.$i.'_Th_Marks'] ?>
                            </td>
                            <td style="<?php ($i < $marksheet_data['total_sub'])? print 'border-bottom-style: hidden;': ''; ?>border-width: 2px; text-align: center; padding: 8px; width: 8%;">
                                <?= $marksheet_data['Sub'.$i.'_Pr_Marks'] ?>
                            </td>
                            <td style="<?php ($i < $marksheet_data['total_sub'])? print 'border-bottom-style: hidden;': ''; ?>border-width: 2px; text-align: center; padding: 8px; width: 10%;">
                                <?= $marksheet_data['Sub'.$i.'_Tot_Marks'] ?>
                            </td>
                            
                            <?php if($i == 1){ ?>
                            <td rowspan="<?=$marksheet_data['total_sub']?>" style="border-width: 2px; text-align: center; padding: 8px; width: 25%;">
                                <font style="font-weight:bold; font-style: italic; margin-top:0;padding-top:0; color: #6a2d33;;">1st Division<br>
                                    300 and above</font><br><br>
                                <font style="font-weight:bold; font-style: italic; margin-top:0;padding-top:0; color: #6a2d33;;">2nd Division<br>
                                    225 and 299</font><br><br>
                                <font style="font-weight:bold; font-style: italic; margin-top:0;padding-top:0; color: #6a2d33;;">3rd Division<br>
                                    **150/153/156/159 and 224</font><br><br>
                                <font style="float: left; font-size: 11px; border-color: #6a2d33; font-weight: 500;">**</font><br>
                                <font style="float: left; font-size: 11px; border-color: #6a2d33; font-weight: 500;">150 - Without practical subjects.</font><br>
                                <font style="float: left; font-size: 11px; border-color: #6a2d33; font-weight: 500;">153 - With one practical subject.</font><br>
                                <font style="float: left; font-size: 11px; border-color: #6a2d33; font-weight: 500;">156 - With two practical subjects.</font><br>
                                <font style="float: left; font-size: 11px; border-color: #6a2d33; font-weight: 500;">159 - With three practical subjects.</font>
                            </td>
                            <?php } ?>
                            
                        </tr>
                        <?php }} ?> 
                        
                        <tr>
                            <td style="border-width: 2px; text-align: left; padding: 8px; width: 10%" colspan="5">
                                <font style="font-weight: 600; font-size: 18px;">GRAND TOTAL</font>
                                <font style="font-weight: 600; font-size: 18px; float: right;"><?= $marksheet_data['Total_Marks_in_Words'] ?></font>
                                <font style="font-weight: 600; font-size: 16px;">(Out of 500 Marks)</font><br>
                                <font style="font-weight: 600; font-size: 13px;">(Marks of subjects excluding the fourth elective)</font>
                            </td>
                            <td style="border-width: 2px; text-align: center; width: 10%;">
                                <?= $marksheet_data['Total_Marks_in_Figure'] ?>
                            </td>
                            <td style="border-bottom-style: hidden; border-width: 2px; text-align: center; padding: 8px; width: 25%;">
                                <?= $marksheet_data['Result'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-width: 2px; text-align: left; padding: 8px; width: 10%" colspan="5">
                                <font style="font-weight: 600; font-size: 18px;">ENVE - GRADE ON ENVIRONMENTAL EDUCATION</font>
                            </td>
                            <td style="border-width: 2px; text-align: center; padding: 8px; width: 10%;">
                                <?= $marksheet_data['ENVE_Grade'] ?>
                            </td>
                            <td style="border-width: 2px; text-align: center; padding: 8px; width: 25%;">

                            </td>
                        </tr>
                    </table>
                </div>
                <div class="sign_div" style="margin-top: 80px;">
                    <table width="100%">
                        <tr>
                            <td style="width: 50%">
                                <table width="50%" style="border: 2px solid;" border="1">
                                    <tr>
                                        <td style="width: 40%; border-bottom-style: hidden; border-right-style: hidden;">
                                        </td>
                                        <td style="border-bottom-style: hidden; border-right-style: hidden; width: 30%; text-align: center;">
                                            <u style="font-weight: 600; color: #6a2d33;">THEORY</u>
                                        </td>
                                        <td style="border-bottom-style: hidden;  width: 30%; text-align: center;">
                                            <u style="font-weight: 600; color: #6a2d33;">PRACTICAL</u>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom-style: hidden; border-right-style: hidden; width: 40%; text-align: left; color: #6a2d33;">
                                            MARKS :
                                        </td>
                                        <td style="border-bottom-style: hidden; border-right-style: hidden; width: 30%; text-align: center; color: #6a2d33;">
                                            100/70
                                        </td>
                                        <td style="border-bottom-style: hidden; width: 30%; text-align: center; color: #6a2d33;">
                                            30
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style=" border-right-style: hidden; width: 40% text-align: left; color: #6a2d33;">
                                            PASS MARKS :
                                        </td>
                                        <td style=" border-right-style: hidden; width: 30%; text-align: center; color: #6a2d33;">
                                            30/21
                                        </td>
                                        <td style="width: 30%; text-align: center; color: #6a2d33;">
                                            12
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 20%">
                                <?php $qrcode = base64_encode(file_get_contents(FCPATH . $qr));
                                ?>
                                <img src="data:image/png;base64,<?php echo $qrcode ?>" class="passport-photo">
                            </td>
                            <td style="text-align:center;" style="width: 30%">
                                <font style="font-size: 16px; color: #6a2d33;font-family: Times New Roman, Times, serif;">Controller of Examinations<br>Assam Higher Secondary Education Council<br>Guwahati-21</font>
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