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
        top: 60%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.1;
    }

    .topbar {
        width: 100%;
        text-align: justify;
        margin-top: 5px;
        line-height: 1;
    }

    .topbar p {
        font-size: 20px;
        color: #1539ad;
    }

    .data-text {
        font-size: 16px !important;
        font-family: Times New Roman, Times, serif;
        font-weight: bold;
    }

    .label-text {
        font-size: 16px !important;
/*        font-family: "Edwardian Script ITC", cursive;*/
        font-weight: 570;
        color: #6274de;
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
        width: 10%;
        height: 10%;
    }

    .passport-photo {
        width: 50px;
        height: 50px;
    }

    .image-container {
        position: relative;
        display: inline-block;
    }

    .image-text {
        position: absolute;
        top: 0;
        left: 0;
        padding: 10px;
        font-size: 16px;
        font-weight: bold;
        text-align: justify;
        margin-top: 250px;
        margin-left: 335px;
    }

    .qr-text {
        position: absolute;
        top: 0;
        left: 0;
        padding: 10px;
        font-size: 16px;
        font-weight: bold;
        text-align: justify;
        margin-top: 10px;
        margin-left: 1px;
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

                            <td class="photo-td">
                                <div class="row image-container" style="display: flex; justify-content: center; align-items: center;">

                                    <?php $qrcode = base64_encode(file_get_contents(FCPATH . $qr));
                                    ?>
                                    <div class="qr-text data-text" style="text-align:center;font-weight:bold">
                                        <p>SL NO: <?= $marksheet_data['Certificate_Serial_No'] ?></p>
                                        <img src="data:image/png;base64,<?php echo $qrcode ?>" class="passport-photo">
                                        <br>
                                        DUPLICATE COPY
                                    </div>
                                    <img class="img" style="margin-top:40px" src="<?= base_url("assets/frontend/images/ahsec_pc.png") ?>" />
                                    <div class="image-text data-text" style="text-align:center;font-weight:bold">
                                        <p>অসম উচ্চতৰ মাধ্যমিক শিক্ষা পৰিষদ<br>BAMUNIMAIDAM : GUWAHATI- 781021<br>PASS CERTIFICATE</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="margin-top:20px; background-color: #f5e4ef;">
                        <tbody>
                            <tr>
                                <td class="label-text">This is to certify that</td>
                                <td class="data-text" colspan="4"><?= $reg_data->candidate_name ?></td>
                            </tr>
                            <tr>
                                <td class="label-text">Son/ Daughter of</td>
                                <td class="data-text"><?= $reg_data->father_name ?><br></td>
                                <td class="label-text">&</td>
                                <td class="data-text" colspan="2" style="text-align:center;"><?= $reg_data->mother_name ?></td>
                            </tr>
                            <tr>
                                <td class="label-text">Roll</td>
                                <td class="data-text"><?= $marksheet_data['Roll'] ?></td>
                                <td class="label-text">No</td>
                                <td class="data-text" colspan="2" style="text-align:center;"><?= $marksheet_data['No'] ?></td>
                            </tr>
                            <tr>
                                <td class="label-text" colspan="3">passed the Higher Secondary Final Examination, <?= DateTime::createFromFormat('d-m-Y', $marksheet_data['Date'])->format('Y') ?> of this council in</td>
                                <td class="data-text"><?= $marksheet_data['Stream'] ?></td>
                            </tr>
                            <tr>
                                <td class="label-text" colspan="2">and was placed in </td>
                                <td class="data-text"><?= $marksheet_data['Result'] ?></td>
                                <td class="label-text" style="text-align:right;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="sign_div" style="margin-top:40px;">
                    <!-- <p style="text-align: right;">Yours Faithfully,</p> -->
                    <table width="100%">
                        <tr>
                            <td style="text-align:left;font-weight:bold">
                                <i>Guwahati-781021<i><br>Date: <?=$marksheet_data['Date']?></p>
                            </td>
                            <td></td>
                            <td style="text-align:right;font-weight:bold">
                                <p>Sd/-<br>SECRETARY</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <img src="<?= base_url('assets/frontend/images/logo_ahsec.png') ?>" alt="Watermark" width="20%" height="25%" class="watermark">
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