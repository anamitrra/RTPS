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
            /* margin-top: 200px;
            margin-bottom: 0;
            padding-bottom: 0;
            text-align: right; */
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: right;


            /* position: absolute; 
            bottom: 0; */

        }

        .info b {
            text-transform: capitalize;
        }

        .passport-photo {
            width: 100px;
            height: 100px;
        }

        .new{
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

    </style>
</head>

<body>
    <div class="wrapper">
        <div class="main">
            <div class="topbar">
                <img class="img" src="<?= base_url("assets/frontend/images/acmr-cp-cme.jpg") ?>" style="width:100px;float: left; margin-left: 20px;" />

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
                <p class="body-text">To, <br>  &nbsp; &nbsp;&nbsp;&nbsp; <?= $data[0]->form_data->applying_org ?></p>
                
                <table width="100%" style="margin-top:10px;padding:0">
                    <tr>
                        <td style="width:10%;vertical-align: top;" class="body-text">Subject :</td>
                        <td style="width:90%;vertical-align: top;" class="body-text">Approval of granting CME Credit points in respect of Continued Medical Education Programme as per National Medical Commission guidelines.</td>
                    </tr>
                    <tr>
                        <td style="width:10%;vertical-align: top;" class="body-text">Ref :</td>
                        <td style="width:90%;vertical-align: top;" class="body-text">Your application No: <?= $data[0]->service_data->appl_ref_no ?></td>
                    </tr>
                </table>
            </div>
            
            <div class="info">
                <p class="body-text">Sir,</p>                
                    <p class="body-text"> With reference to your letter on the subject quoted above, I am to inform you that for the Continued Medical Education 
                        Programme to be held from <?= $data[0]->form_data->start_date ?> to <?= $data[0]->form_data->end_date ?> at <?= $data[0]->form_data->conference_location ?> which is being 
                        organized by <?= $data[0]->form_data->applying_org ?>, Assam Council of Medical Registration shall grant 3 (three) credit points to 
                        Speaker and 2 (two) credit points to delegates for each day of CME programme covering minimum of 8 (eight) hours of academic session. 
                        This is as per NMC guidelines. </p>
                </div><br><br>

                <table width="100%" style="text-align: right;">
                    <tr>                        
                        <td style="width:60%;text-align: center;" class="body-text"></td>
                        <td style="text-align: center;" class="body-text">
                            Yours Faithfully,<br><br><br>
                            Registrar<br>
                            Assam Council of Medical Registration
                        </td>
                    </tr>
                </table>

        </div>
    </div>
</body>

</html>