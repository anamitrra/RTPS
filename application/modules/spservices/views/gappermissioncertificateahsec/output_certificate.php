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
            border: 2px solid #808080;
            padding: 10px;
        }

        .topbar {
            width: 100%;
            margin-top: 25px;
            text-align: justify;
            line-height: 1.5;
            letter-spacing: 1px;
        }

        .topbar td,
        p {
            font-size: 20px;
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
            margin-top: 30px;
            text-align: justify;
            line-height: 1.5;
            letter-spacing: 1px;
        }

        .info table td,
        th {
            border: 1px solid #990000;
            border-collapse: collapse;
            background-color: #ffffcc;
        }

        .sign_div {
            margin-top: 230px;
            margin-bottom: 0;
            padding-bottom: 0;


            /* position: absolute;
            bottom: 0; */

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
                <p style="text-align:center;font-weight:bold;margin-top:0;padding-top:0">ASSAM COUNCIL OF MEDICAL REGISTRATION<br> CERTIjhuuhuhuuuuCATION</p>
                <p style="text-align:center;font-weight:bold;margin-top:0;padding-top:0;font-size:10px;">OFFICE OF THE ASSAM COUNCIL OF MEDICAL REGISTRATION, GUWAHATI</p>
                <table width="100%">
                    <tr>
                        <td style="width:60%;vertical-align: top">No: <b><?= $certificate_no ?></b></td>
                        <td style="width:40%;vertical-align: top;text-align: right;">Dated, Guwahati, the <?php echo date('d-m-Y') ?></td>
                    </tr>
                </table>

            </div>

            <div class="sign_div">
                <table width="100%">
                    <tr>
                        <td width="50%"></td>
                        <td style="text-align:center;font-weight:bold">
                            <p>Registrar<br>Assam Council of Medical Registration,<br>Six mile, Khanapara, Ghy-22</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>