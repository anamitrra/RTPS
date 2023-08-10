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
    .border-design {
        border: 2px solid #000;
        border-radius: 10px;
        padding: 20px;
    }
</style>

<div class="container my-2">
    <div class="card shadow-sm mt-2">
        <div class="card-body border-design">
            <form id="myfrm" method="POST" action="<?= base_url('spservices/upms/dscsign/withoutpdfsigned/') ?>" enctype="multipart/form-data">
                <input id="obj_id" name="obj_id" value="<?= $data[0]->_id->{'$id'} ?>" type="hidden" />
                <input id="certificate_no" name="certificate_no" value="<?= $certificate_no ?>" type="hidden" />
                <input id="certificate_path" name="certificate_path" value="<?= $certificate_path ?>" type="hidden" />
                <div class="topbar">
                    <table width="100%">
                        <tr>
                            <td style="width:35%">
                                <?php $qrcode = base64_encode(file_get_contents(FCPATH . $qr)); ?>
                                <img src="data:image/png;base64,<?php echo $qrcode ?>" width="20%" style="color: #af7077;"></br>
                                <font style="font-size: 18px; font-weight: 600; font-family: Times New Roman, Times, serif;"><?= $data[0]->service_data->appl_id ?></font>
                            </td>
                            <td style="width:30%">
                                <div class="row" style="display: flex; justify-content: center; align-items: center;">
                                    <img class="img" src="<?= base_url("assets/frontend/images/logo_ahsec.png") ?>" width="80" height="80" />
                                </div>
                            </td>
                            <td style="width:35%;text-align: right;">
                                <font style="color: #af7077;font-size: 18px;">Sl. No.</font>
                                <font style="font-size: 18px; font-weight: 600; font-family: Times New Roman, Times, serif;"><?= $reg_data->sl_no ?></font>
                            </td>
                        </tr>
                    </table>
                    <p style="text-align:center;font-weight:bold;margin-top:0;padding-top:0; color: #af7077;">ASSAM HIGHER SECONDARY EDUCATION COUNCIL : GUWAHATI - 21 <br> REGISTRATION CERTIFICATE</p><br>

                    <div style="display: flex;">
                        <div style="width: 70%">
                            <table width="100%">
                                <tr>
                                    <td style="width:30%" class="custom-text">
                                        <font style="font-size: 20px; color: #af7077;font-family: Times New Roman, Times, serif; font-style: italic; font-weight: 600;">Code No.</font>
                                    </td>
                                    <td style="width:70%" colspan="3">
                                        <font style="font-size: 20px; font-family: Times New Roman, Times, serif; font-weight: 600;"><?= $reg_data->institution_code ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:30%" class="custom-text">
                                        <font style="font-size: 20px; color: #af7077;font-family: Times New Roman, Times, serif; font-style: italic; font-weight: 600;">Certfied that</font>
                                    </td>
                                    <td style="width:70%" colspan="3">
                                        <font style="font-size: 20px; font-family: Times New Roman, Times, serif; font-weight: 600;"><?= $reg_data->candidate_name ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:30%" class="custom-text">
                                        <font style="font-size: 20px; color: #af7077;font-family: Times New Roman, Times, serif; font-style: italic; font-weight: 600;">Son/Daughter of</font>
                                    </td>
                                    <td style="width:70%" colspan="3">
                                        <font style="font-size: 20px; font-family: Times New Roman, Times, serif; font-weight: 600;">
                                        <?= $reg_data->father_name ?><br> <?= $reg_data->mother_name ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:30%" class="custom-text">
                                        <font style="font-size: 20px; color: #af7077;font-family: Times New Roman, Times, serif; font-style: italic; font-weight: 600;">a student of</font>
                                    </td>
                                    <td style="width:70%" colspan="3">
                                        <font style="font-size: 20px; font-family: Times New Roman, Times, serif; font-weight: 600;"><?= $reg_data->institution_name ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:100%" class="custom-text" colspan="4">
                                        <font style="font-size: 20px; color: #af7077;font-family: Times New Roman, Times, serif; font-style: italic; font-weight: 600;">is registered as a student of this council</font>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="width: 30%;">
                            <?php $photo = base64_encode(file_get_contents(FCPATH . $data[0]->form_data->passport_photo)); ?>
                            <img src="data:image/png;base64,<?php echo $photo ?>" width="130" style="float: right; margin-right: 30px;">
                       
                        </div>
                    </div>
                    <div style="display: flex;">
                        <div style="width: 70%">
                            <table width="100%">
                                <tr>
                                    <td class="custom-text">
                                        <font style="font-size: 20px; color: #af7077;font-family: Times New Roman, Times, serif; font-style: italic; font-weight: 600;">His/Her Registration Number is</font>
                                    </td>
                                    <td>
                                        <font style="font-size: 20px; font-family: Times New Roman, Times, serif; font-weight: 600;"><?= $reg_data->registration_number ?></font>
                                    </td>
                                    <td class="custom-text">
                                        <font style="font-size: 20px; color: #af7077;font-family: Times New Roman, Times, serif; font-style: italic; font-weight: 600;">of session</font>
                                    </td>
                                    <td>
                                        <font style="font-size: 20px; font-family: Times New Roman, Times, serif; font-weight: 600;"><?= $reg_data->registration_session ?></font>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="width: 30%"></div>
                    </div>
                    <div style="display: flex; margin-top: 20px;">
                        <div style="width: 30%">
                            <font style="font-size: 18px; float: left; color: #af7077;font-family: Times New Roman, Times, serif; font-style: italic;">His/Her Subjects for Examination are</font>
                        </div>
                        <div style="width: 70%">
                            <table width="100%" border="1">
                                <tr>
                                    <td colspan="2" style="text-align: center;">
                                        <font style="font-size: 18px; color: #af7077;font-family: Times New Roman, Times, serif;">CORE SUBJECTS</font>
                                    </td>
                                    <td colspan="4" style="text-align: center;">
                                        <font style="font-size: 18px; color: #af7077;font-family: Times New Roman, Times, serif;">ELECTIVE SUBJECTS</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-weight: 600;"><?= $reg_data->sub_1 ?></td>
                                    <td style="text-align: center; font-weight: 600;"><?= $reg_data->sub_2 ?></td>
                                    <td style="text-align: center; font-weight: 600;"><?= $reg_data->sub_3 ?></td>
                                    <td style="text-align: center; font-weight: 600;"><?= $reg_data->sub_4 ?></td>
                                    <td style="text-align: center; font-weight: 600;"><?= $reg_data->sub_5 ?></td>
                                    <td style="text-align: center; font-weight: 600;"><?= $reg_data->sub_6 ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <p style="font-size: 30px; color:red;font-family: Times New Roman, Times, serif;">Internet Copy</p>
                </div>
                <div class="sign_div" style="margin-top: 30px;">
                    <table width="100%">
                        <tr>
                            <td style="width: 30%">
                                <font class="custom-text" style="font-size: 20px; color: #af7077;font-family: Times New Roman, Times, serif;">Date : </font>
                                <font style="font-size: 19px; font-family: Times New Roman, Times, serif; font-weight: 600;"><?= $reg_data->issue_date ?></font><br>
                                <font style="font-size: 19px; font-family: Times New Roman, Times, serif; font-weight: 600;"><?= $reg_data->sl_no ?></font>
                            </td>
                            <td style="text-align: center;" style="width: 40%">
                                <font style="font-size: 19px; font-family: Times New Roman, Times, serif; font-weight: 600;">Dy. Secretary (RPR)<br>
                                    AHSEC</font>
                            </td>
                            <td style="text-align:right;" style="width: 30%">
                                <font style="font-size: 19px; font-family: Times New Roman, Times, serif; font-weight: 600;">Sd/-</font><br>
                                <font style="font-size: 18px; color: #af7077;font-family: Times New Roman, Times, serif;">Secretary<br>Assam Higher Secondary Education Council<br>
                                    Bamunimaidam, Guwahati-21</font>
                            </td>
                        </tr>
                    </table>
                </div>
                <img src="<?= base_url('assets/frontend/images/logo_ahsec.png') ?>" alt="Watermark" class="watermark">
        </div>
        <div class="card-footer text-center no-print">
            <?php if($user_type=='official'){?>
            <a href="<?= base_url('spservices/upms/myapplications/process/' . $data[0]->_id->{'$id'}) ?>" class="btn btn-primary">
                <i class="fa fa-angle-double-left"></i> BACK
            </a>
            <?php } ?>
            <button class="btn btn-info" id="printBtn" type="button">
                <i class="fa fa-print"></i> PRINT
            </button>
            <?php if($user_type=='official'){?>
            <button class="btn btn-success frmbtn" id="SAVE" type="button">
                <i class="fa fa-check" aria-hidden="true"></i> DELIVER
            </button>
            <?php } ?>
            </from>
        </div>
    </div>
</div>