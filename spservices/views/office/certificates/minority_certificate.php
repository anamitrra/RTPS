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
            background-image: url('<?php echo FCPATH . 'assets/frontend/images/watermark.png' ?>');
            background-repeat: no-repeat;
            background-attachment: fixed;
            /* background-size: cover; */
            background-size: 600px 400px;
            background-position: 50% 65%;
            font-size: 15px;
        }

        .wrapper {
            border: 2px solid #808080;
            padding: 10px;
        }

        .topbar {
            width: 100%;
        }

        .topbar td,
        p {
            font-size: 17px;
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
            font-size: 15px;
            /* margin-top:10px; */
            text-align: justify;
            line-height: 1.5;
            letter-spacing: 1px;
        }

        .sign_div {
            margin-top: 20px;
            margin-bottom: 0;
            padding-bottom: 0;
            /* position: absolute; */
            /* bottom: 0 */
        }

        .info b {
            text-transform: capitalize;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="main">
            <div class="topbar">
                <table width="100%">
                    <tr>
                        <td style="width:45%;vertical-align: top">Certificate Number:<br> <b><?= $certificate_no ?></b></td>
                        <td style="width:55%;vertical-align: top"><img src="<?= FCPATH . "assets/frontend/images/assam_govt.png" ?>" style="width:90px;"></td>
                    </tr>
                </table>
                <p style="text-align:center;margin-top:0;padding-top:0">GOVERNMENT OF ASSAM</p>
                <table width="100%" style="margin:0;padding:0">
                    <tr>
                        <td style="width:10%"></td>
                        <td style="vertical-align: top;text-align: center;">
                            <p style="text-align:center;margin-bottom:0;">OFFICE OF THE DEPUTY COMMISSIONER, <?= $data[0]->form_data->pa_district_name ?> DISTRICT </p>
                            <p style="font-weight:bold;font-size:16px;margin-top:3px;">{Sec.2(c) of the National Commission for Minorities Act, 1992 (19 of 1992)}</p>
                            <p style="font-weight:bold;font-size:20px">Minority Community Certificate</p>
                        </td>
                        <?php $photo = base64_encode(file_get_contents(FCPATH . $data[0]->form_data->passport_photo)); ?>
                        <td style="width:12%;vertical-align: top;"><img src="data:image/png;base64, '.<?php echo $photo ?>.'" style="width:100%; height:110px;margin:0"></td>
                    </tr>
                </table>
            </div>
            <div class="info">
                <p>This is to certify that Shri/Smti <b style="text-transform: uppercase;"><?= $data[0]->form_data->applicant_name ?></b> S/O, D/O (Father’s Name/Spouse Name) <b><?= $data[0]->form_data->father_name ?></b>
                    (Mother’s Name) <b><?= $data[0]->form_data->mother_name ?></b> in the District <b><?= $data[0]->form_data->pa_district_name ?></b> of the State of Assam belongs to the <b><?= $data[0]->form_data->community ?></b>
                    Minority Community as defined under Sec.2(c) of the National Commission for Minorities Act, 1992 (19 of 1992).
                    Shri/Smti <b><?= $data[0]->form_data->applicant_name ?></b> and his/her family resides in Village/Town <b><?= $data[0]->form_data->pa_village ?></b>
                    P.O. <b><?= $data[0]->form_data->pa_post_office ?></b> P.S <b><?= ($data[0]->form_data->pa_police_station) ? $data[0]->form_data->pa_police_station : '' ?></b> at <b><?= $data[0]->form_data->pa_district_name ?></b> District of the State of Assam.</p>
            </div>
            <div class="sign_div">
                <table width="100%">
                    <tr>
                        <td style="vertical-align: top">
                            <p>Place : <?= $data[0]->form_data->pa_district_name ?><br>
                                Date : <?php echo date('d-m-Y') ?></p>
                        </td>
                        <!-- <td style="text-align:center;font-weight:bold">
                            <p>Additional Deputy Commissioner <br> <?= $data[0]->form_data->pa_district_name ?> <br> State: Assam</p>
                        </td> -->
                    </tr>
                    <tr>
                        <td>
                            <?php $qrcode = base64_encode(file_get_contents(FCPATH . $qr));
                            ?>
                            <img src="data:image/png;base64, '.<?php echo $qrcode ?>.'" width="20%">
                        </td>
                        <td style="text-align:center;font-weight:bold">
                        <p><?= $this->session->userdata('designation') ?> <br> <?= $data[0]->form_data->pa_district_name ?> <br> State: Assam</p>
                            <!-- <p>Additional Deputy Commissioner <br> <?= $data[0]->form_data->pa_district_name ?> <br> State: Assam</p> -->
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>