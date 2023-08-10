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
            /* margin-top: 15px; */
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
            background-color: #ffffcc;
        }

        .sign_div {
            margin-top: 10px;
            margin-bottom: 0;
            padding-bottom: 0;


            /* position: absolute;
            bottom: 0; */

        }

        .info b {
            text-transform: capitalize;
        }

.logos {
  overflow: auto;
}

.left,
.right {
  width: 50%;
  float: left;
}

.sign_div {
  overflow: auto;
}

.box {
  width: 25%;
  float: left;
}

.qr-div {
  border: 1px solid #000000;
}
.photo-div{
    border: 1px solid #000000;
}

    .bordered-div {
        border: 1px solid black;
        padding: 10px;
    }

        .photo-signature-container {
        border: 1px solid black;
        padding: 20px;
        text-align: center;
    }

    .photo {
        margin-bottom: 10px;
    }


    </style>
</head>

<body>

<?php 

$passport = FCPATH . $data[0]->form_data->passport_photo;
$passportPath = $passport;
$passportData = file_get_contents($passportPath);
$passportBase64Image = base64_encode($passportData);


$signature = FCPATH . $data[0]->form_data->signature;
$signaturePath = $signature;
$signatureData = file_get_contents($signaturePath);
$signatureBase64Image = base64_encode($signatureData);



$currentDate = date('d-m-Y'); 
$Certificate_valid_timeline = date('d-m-Y', strtotime($currentDate . ' +5 years'));





?>


   <div class="wrapper">
    <div class="main">


        <!-- Logos div start -->

        <!-- <?php echo FCPATH . 'assets/frontend/images/watermark.png' ?> -->

        <div class="logos"> 

            <!-- <div class="left" ><img src="assets\acmr\logos\image-001.jpg" style="width: 20%; height: auto;" alt="Description of the image"></div> -->
        <div class="left" ><img src="<?=FCPATH.'assets\acmr\logos\image-001.jpg'?>" style="width: 20%; height: auto; alt="Description of the image"></div>

            
        <div style="text-align:right" class="right" ><img src="<?=FCPATH.'assets\acmr\logos\image-001.jpg'?>" style="width: 20%; height: auto;alt="Description of the image"></div>
        </div>
        <!-- Logos div end -->


        <!-- Topbar start -->
        <div class="topbar">
            <p style="font-size:14px;text-align:center;font-weight:bold;margin-top:0;padding-top:0">
                <span style="color:#244d9a">ASSAM COUNCIL OF MEDICAL REGISTRATION</span><br>
                <span style="color:#c10505">CERTIFICATE OF REGISTRATION</span><br>
                OFFICE OF THE ASSAM COUNCIL OF MEDICAL REGISTRATION<br>
                GUWAHATI, ASSAM (INDIA)
            </p>
            <p style="text-align:center;font-weight:bold;margin-top:0;padding-top:0;font-size:10px;">
                (Constituted under The Assam Medical Council Act, 1999, vide Government of Assam Notification No. LGL.60/98/18 dated 18th December, 1999)
            </p>
        </div>
        <!-- Topbar end -->


        <!-- Info start -->
        <div class="info">
            <table width="100%" style="text-align:center">
                <thead>
                    <tr>
                        <th style="text-align:center;font-weight:bold">Registration No.</th>
                        <th style="text-align:center;font-weight:bold">Date of Registration</th>
                        <th style="text-align:center;font-weight:bold">Name</th>
                        <th style="text-align:center;font-weight:bold">Father's Name</th>
                        <th style="text-align:center;font-weight:bold">Qualification</th>
                        <th style="text-align:center;font-weight:bold">Permanent Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="20%">AS/<?= date('Y') ?>/PER/ACMR/...</td>
                        <td width="20%"><?= $currentDate ?></td>
                        <td width="20%"><?= $data[0]->form_data->applicant_name ?></td>
                        <td width="20%"><?= $data[0]->form_data->father_name ?></td>
                        <td width="20%">Qualification</td>
                        <td width="20%"><?= $data[0]->form_data->permanent_addr ?></td>
                    </tr>
                </tbody>
            </table>
            <p style="text-align:center;font-weight:bold;margin-top:0;padding-top:0;font-size:10px;">
                It is hereby certified that this is a true copy of the entries of the above specified name in columns 1, 2, 3, 4, 5, 6, and 7 of the Register of Registered Practitioners.
            </p>
        </div>
        <!-- Info end -->

        <!-- Signature part -->
<br>
<div style="width: 100%">
    <div style="display: inline-block; width: 24%;">
     <?php $qrcode = base64_encode(file_get_contents(FCPATH . $qr));?>
        <div class="" style="height:70px; width:70px"><img src="data:image/png;base64,<?php echo $qrcode ?>" class="">
    </div>
    </div>


    <div style="display: inline-block; width: 24%; ">
        <!-- Content for the second div -->
        <div class="bordered-div" style="height:70px; width:120px; position: relative; overflow: hidden;">
        <img style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;" src="data:image/png;base64,<?= $passportBase64Image ?>"/>
    </div>
            <div class="bordered-div" style="height:20px; width:120px;position: relative; overflow: hidden;" >
    <img style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;" src="data:image/png;base64,<?= $signatureBase64Image ?>"/>
    </div>
    </div>
    <div style="display: inline-block; width: 24%;">
        <!-- Content for the third div -->
        *This certificate is valid upto <?= $Certificate_valid_timeline ?>
    </div>
    <div style="display: inline-block; width: 24%;">
        <!-- Content for the fourth div -->
Registrar <br>
Assam Council of Medical Registration
Sixmile, Khanapara, Guwahati-22
    </div>
</div>
<!-- Signature end -->

<hr>

<h5 style="text-align:center">IMPORTANT NOTICE</h5>
<ul style="font-size:10px">
    <li>
        Every Registered Medical Practitioner should be careful to send to the Registrar of any change in his/her address and also answer all enquiries that may be sent to
him/her by the Registrar in regard thereto, in order that his correct address may be duly inserted in the Register of Registered Practitioners.
    </li>
    <li>
        A copy of Annual Medical register wherein the name first appears will be supplied gratis to every person registered provided that before the end of the year of
publication, application be made for such copy to the Registrar, accompanied with required postal charges.
    </li>
    <li>
        After the publication of the name in the ANNUAL MEDICAL REGISTER, the last edition of the Register alone is the legal evidence of Registration.
    </li>

</ul>



        <!-- Ending -->
    </div>
</div>


</body>

</html>