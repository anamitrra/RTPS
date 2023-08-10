<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Output certificate</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link defer href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        * {
            margin: 6px 0;
            /* padding: 0; */
            font-family: sans-serif;
        }

        body {
            padding: 0 20px;
            /*background-image: url('<?php echo FCPATH . 'assets/frontend/images/watermark.png' ?>');*/
            background-repeat: no-repeat;
            background-attachment: fixed;
            /* background-size: cover; */
            /* background-size: 600px 400px;
            background-position: 50% 65%; */

        }

        .wrapper {
            /* border: 2px solid #808080; */
            padding: 30px;
        }

        .topbar {
            width: 100%;
            text-align: justify;
            margin-top: 25px;
            line-height: 1.5;
        }

        .header-text {
            font-size: 14px !important;
            font-family: Times New Roman, Times, serif;
            font-weight: bold;
        }

        .body-text {
            font-size: 14px !important;
            font-family: Times New Roman, Times, serif;
        }

        .topbar td,
        p {
            font-size: 15px;
        }

        .topbar .left {
            width: 50%
        }

        .topbar .right {
            width: 50%;
        }

        .topbar img {
            width: 70px;
        }

        .info {
            font-size: 10px;
            margin-top: 20px;
            text-align: justify;
            line-height: 1.5;
        }

        .sign_div {
            margin-top: 90px;
            margin-bottom: 0;
            padding-bottom: 0;


            /* position: absolute; 
            bottom: 0; */

        }

        .info b {
            text-transform: capitalize;
        }

        .photo-td {
            width: 20%;
        }

        .passport-photo {
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="main">
            <div class="topbar">
                <img class="img" src="assets/frontend/images/logo_acmr.jpg" style="width:100px;float: left; margin-left: 20px;" />

                <p style="text-align:center;" class="header-text">
                    ASSAM COUNCIL OF MEDICAL REGISTRATION ::ASSAM:: <br>
                    OFFICE OF THE REGISTRAR, ASSAM COUNCIL OF MEDICAL REGISTRATION <br>
                    Six Mile, Khanapara, Guwahati (Assam)-781022 <br>
                    Email: assamcmr2016@gmail.com <br>
                </p>
                <hr>
                <table width="100%">
                    <tr>
                        <td style="width:60%;vertical-align: top" class="body-text">No: <?= $certificate_no ?></td>
                        <td style="width:40%;vertical-align: top;text-align: right;" class="body-text">Dated, Guwahati, the <?php echo date('d-m-Y') ?></td>
                    </tr>
                </table>
                <p class="body-text">To,</p>
                <table width="100%" style="margin-left:50px;padding:0">
                    <tr>
                        <td style="width:10%" class="body-text">The Registrar,</td>
                    </tr>
                    <tr>
                        <td style="width:12%;vertical-align: top;" class="body-text"><?= $data[0]->form_data->registering_smc ?></td>
                    </tr>
                </table>
                <table width="100%" style="margin-top:10px;padding:0">
                    <tr>
                        <td style="width:10%;vertical-align: top;" class="body-text">Subject :</td>
                        <td style="width:90%;vertical-align: top;" class="body-text">Confirmation of Registration No. <?= $data[0]->form_data->permanent_reg_no ?><?= ', ' . $data[0]->form_data->additional_degree_reg_no ?> dtd <?= $data[0]->form_data->permanent_reg_date ?><?= ', ' . $data[0]->form_data->additional_degree_reg_date ?> of <?= $data[0]->form_data->applicant_name ?> <?= $data[0]->form_data->working_place_add ?></td>
                    </tr>
                    <tr>
                        <td style="width:10%;vertical-align: top;" class="body-text">Ref :</td>
                        <td style="width:90%;vertical-align: top;" class="body-text">Application No: <?= $data[0]->service_data->appl_ref_no ?></td>
                    </tr>
                </table>
            </div>
            <div class="info">
                <p class="body-text">Sir,</p>
                <p class="body-text">With reference to the application quoted above, I have the honour to inform you that on verification of records it is found that the name of one Dr. <b style="text-transform: uppercase;"><?= $data[0]->form_data->applicant_name ?></b> bearing the registration no.<?= $data[0]->form_data->permanent_reg_no ?><?= ', ' . $data[0]->form_data->additional_degree_reg_no ?> dtd <?= $data[0]->form_data->permanent_reg_date ?><?= ', ' . $data[0]->form_data->additional_degree_reg_date ?> is still borne in the Register of Registered Medical Practitioners maintained by the Assam Council of Medical Registration and no disciplinary proceeding had ever been taken against him/ her nor is in progress till date. The Council has no objection in registering his/ her name in the Register of Registered Medical Practitioner maintained by <?= $data[0]->form_data->registering_smc ?> under section 27 of the Indian Medical Act, 1956</p>
            </div>
            <div class="sign_div">
                <!-- <p style="text-align: right;">Yours Faithfully,</p> -->
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <?php $qrcode = base64_encode(file_get_contents(FCPATH . $qr));
                            ?>
                            <div class="qr-text data-text" style="text-align:center;font-weight:bold">
                                <img src="data:image/png;base64,<?php echo $qrcode ?>" class="passport-photo">
                            </div>

                        </td>
                        <td width="30%" class="photo-td">
                            <?php
                            $photo = base64_encode(file_get_contents(FCPATH . $data[0]->form_data->passport_photo));
                            $signature = base64_encode(file_get_contents(FCPATH . $data[0]->form_data->signature));
                            ?>
                            <img src="data:image/png;base64,<?php echo $photo ?>" class="passport-photo"><br>
                            <img src="data:image/png;base64,<?php echo $signature ?>" class="passport-photo">
                        </td>
                        <td style="text-align:center;font-weight:bold">
                            <p class="body-text">Registrar<br>Assam Council of Medical Registration,<br>Six mile, Khanapara, Ghy-22</p>
                        </td>
                    </tr>
                </table>
            </div>
            <hr>
            <div>
                <table width="100%">
                    <tr>
                        <td style="text-align:justify;font-weight:bold">
                            <p class="body-text">N.B. : The no objection issued by this Council for registering his/her name in the Register of Registered Medical Practitioners maintained by <?= $data[0]->form_data->registering_smc ?> shall be valid for 3 (three) months from the date of issue of this letter.</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>